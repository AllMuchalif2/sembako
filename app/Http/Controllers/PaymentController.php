<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function pay($order_id)
    {
        $transaction = Transaction::where('order_id', $order_id)->where('user_id', Auth::id())->firstOrFail();

        // COD orders cannot use payment retry
        if ($transaction->payment_method === Transaction::PAYMENT_METHOD_COD) {
            return redirect()->route('transactions.index')->with('error', 'Pesanan COD tidak memerlukan pembayaran online.');
        }

        if ($transaction->status != 'pending') {
            return redirect()->route('transactions.index')->with('error', 'Transaksi ini tidak dapat dibayar lagi.');
        }

        // Generate new Snap token for retry
        try {
            $snapToken = $this->generateSnapToken($transaction);
            return view('checkout.payment', ['snapToken' => $snapToken, 'client_key' => config('midtrans.client_key'), 'order' => $transaction]);
        } catch (\Exception $e) {
            Log::error('Pay method: Failed to generate Snap token.', ['error' => $e->getMessage(), 'order_id' => $order_id]);
            return redirect()->route('transactions.index')->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function callback(Request $request)
    {
        Log::info('Midtrans notification received.', $request->all());

        try {
            // 1. Verifikasi Signature Key
            $signatureKey = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . config('midtrans.server_key'));
            if ($signatureKey != $request->signature_key) {
                Log::warning('Midtrans callback: Invalid signature.', ['order_id' => $request->order_id]);
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            // 2. Ambil data dari request
            $orderId = $request->order_id;
            $transactionStatus = $request->transaction_status;
            $fraudStatus = $request->fraud_status;

            // 3. Cari transaksi berdasarkan order_id
            $transaction = Transaction::where('order_id', $orderId)->first();

            if (!$transaction) {
                Log::warning('Midtrans callback: Transaction not found in DB.', ['order_id_from_request' => $orderId]);
                return response()->json(['message' => 'Transaction not found.'], 404);
            }

            // 4. Jangan proses notifikasi yang sama berulang kali (Idempotency)
            if ($transaction->status === 'diproses' || $transaction->status === 'settlement') {
                Log::info('Midtrans callback: Transaction already marked as success.', ['order_id' => $orderId]);
                return response()->json(['message' => 'Transaction already processed.'], 200);
            }

            // 5. Handle status berdasarkan notifikasi
            if ($transactionStatus == 'settlement') {
                $transaction->update([
                    'payment_status' => 'settlement',
                    'status' => 'diproses',
                ]);
                Log::info('Midtrans callback: Transaction status updated to settlement.', ['order_id' => $orderId]);
            } else if ($transactionStatus == 'capture' && $fraudStatus == 'accept') {
                $transaction->update([
                    'payment_status' => 'settlement',
                    'status' => 'diproses',
                ]);
                Log::info('Midtrans callback: Transaction status updated to success after capture.', ['order_id' => $orderId]);
            } else if ($transactionStatus == 'pending') {
                $transaction->update([
                    'payment_status' => 'pending',
                ]);
            } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                $transaction->update([
                    'payment_status' => 'failed',
                    'status' => 'failed'
                ]);

                // Kembalikan stok produk
                foreach ($transaction->items as $item) {
                    Product::find($item->product_id)->increment('stock', $item->quantity);
                }
                Log::info('Midtrans callback: Transaction failed, stock returned.', ['order_id' => $orderId]);
            } else if ($fraudStatus == 'challenge') {
                $transaction->update([
                    'payment_status' => 'challenge',
                    'status' => 'challenge',
                ]);
                Log::warning('Midtrans callback: Transaction is challenged by FDS.', ['order_id' => $orderId]);
            }

            return response()->json(['message' => 'Notification handled successfully.'], 200);
        } catch (\Exception $e) {
            Log::error('Midtrans callback error: ' . $e->getMessage(), ['request_payload' => $request->all(), 'exception' => $e]);
            return response()->json(['message' => 'Error handling notification.'], 500);
        }
    }

    /**
     * Generate Midtrans Snap token for a transaction
     *
     * @param Transaction $transaction
     * @return string Snap token
     * @throws \Exception
     */
    private function generateSnapToken(Transaction $transaction): string
    {
        // Prepare item details
        $item_details = [];
        foreach ($transaction->items as $item) {
            $item_details[] = [
                'id' => $item->product_id,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'name' => Str::limit($item->product_name, 50),
            ];
        }

        // Prepare Snap parameters
        $params = [
            'transaction_details' => [
                'order_id' => $transaction->order_id,
                'gross_amount' => $transaction->total_amount,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'callbacks' => [
                'finish' => route('checkout.success', ['order_id' => $transaction->order_id]),
                'error' => route('cart.index', ['status' => 'error', 'order_id' => $transaction->order_id]),
                'pending' => route('cart.index', ['status' => 'pending', 'order_id' => $transaction->order_id]),
            ],
            'item_details' => $item_details,
        ];

        Log::info('Generating Midtrans Snap token.', ['order_id' => $transaction->order_id, 'user_id' => Auth::id() ?? 'guest']);

        $snapToken = Snap::getSnapToken($params);
        $transaction->snap_token = $snapToken;
        $transaction->save();

        return $snapToken;
    }
}
