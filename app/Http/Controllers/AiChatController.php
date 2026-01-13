<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GroqService;

use App\Models\Product;
use App\Models\Promo;
use App\Models\StoreSetting;

class AiChatController extends Controller
{
    public function handleChat(Request $request, GroqService $groq)
    {
        $request->validate([
            'message' => 'required|string|max:500'
        ]);

        $message = $request->input('message');

        // 1. Ambil Data Konteks (Produk & Promo)
        // Batasi jumlah agar tidak over token, misalnya ambil yg stok > 0
        $products = Product::where('stock', '>', 0)
            ->select('name', 'price', 'stock')
            ->get()
            ->map(function ($p) {
                return "- {$p->name}: Rp" . number_format($p->price, 0, ',', '.') . " (Stok: {$p->stock})";
            })->join("\n");

        $promos = Promo::where('status', 'active') // Filter status aktif
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->get()
            ->map(function ($p) {
                $benefit = $p->type == 'percentage' ? "Diskon {$p->value}%" : "Potongan Rp" . number_format($p->value, 0, ',', '.');
                return "- KODE PROMO: {$p->code} ({$benefit} - {$p->description})";
            })->join("\n");

        // Ambil store settings untuk social media
        $settings = StoreSetting::getSettings();
        $socialMedia = [];
        if ($settings->social_media_instagram) {
            $socialMedia[] = "Instagram: {$settings->social_media_instagram}";
        }
        if ($settings->social_media_tiktok) {
            $socialMedia[] = "TikTok: {$settings->social_media_tiktok}";
        }
        if ($settings->social_media_whatsapp) {
            $socialMedia[] = "WhatsApp: https://wa.me/{$settings->social_media_whatsapp}";
        }
        $socialMediaText = !empty($socialMedia) ? "\n\nSOCIAL MEDIA KAMI:\n" . implode("\n", $socialMedia) : "";

        $contextData = "DAFTAR PRODUK KAMI SAAT INI:\n$products\n\nDAFTAR PROMO:\n$promos$socialMediaText";

        // 2. System Prompt yang Dipertajam
        $systemPrompt = "Kamu adalah ASISTEN TOKO 'My Mart'. 
Tugasmu: Jawab pertanyaan pelanggan berdasarkan DATA PRODUK di bawah.
toko buka dari jam 05.00 sampai 22.00.
Aturan Penting:
1. JAWABAN HARUS PENDEK, PADAT, DAN TO THE POINT. Maksimal 2-3 kalimat.
2. JANGAN mengarang harga atau stok. Gunakan HANYA data yang diberikan. 
3. Jika produk tidak ada di daftar, katakan 'Maaf, produk tersebut sedang kosong/tidak tersedia'.
4. Jika ditanya kontak/social media, berikan link yang tersedia.
5. Bahasa: Indonesia santai, ramah, tapi tidak bertele-tele.

$contextData";

        $response = $groq->chat($message, $systemPrompt);

        return response()->json([
            'reply' => $response
        ]);
    }
}
