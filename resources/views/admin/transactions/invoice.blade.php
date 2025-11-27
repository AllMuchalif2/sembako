<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $transaction->order_id }}</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <link rel="icon" href="{{ secure_asset('images/logo.png') }}" type="image/png">
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }
        }

        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            font-size: 16px;
            line-height: 24px;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="invoice-box bg-white mt-10 mb-10 rounded-lg">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                {{-- {{ config('app.name', 'Sembako') }} --}}
                                <img src="{{ asset('images/logo.png') }}" style="width:100%; max-width:100px;">
                            </td>
                            <td>
                                Invoice #: {{ $transaction->order_id }}<br>
                                Dibuat: {{ $transaction->created_at->format('d M Y') }}<br>
                                {{-- Status: {{ ucfirst($transaction->status) }} --}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                Pelanggan:<br>
                                {{ $transaction->user->name }}<br>
                                {{ $transaction->user->email }}
                            </td>
                            <td>
                                Dikirim ke:<br>
                                {{ $transaction->shipping_address }}<br>
                                @if ($transaction->notes)
                                    Catatan: {{ $transaction->notes }}
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td>Item</td>
                <td>Harga</td>
            </tr>

            @foreach ($transaction->items as $item)
                <tr class="item">
                    <td>
                        {{ $item->product_name }} ({{ $item->quantity }} x
                        Rp{{ number_format($item->price, 0, ',', '.') }})
                    </td>
                    <td>
                        Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach

            <tr class="item">
                <td></td>
                <td>
                    Subtotal:
                    Rp{{ number_format($transaction->total_amount + $transaction->discount_amount - $transaction->shipping_cost, 0, ',', '.') }}
                </td>
            </tr>

            <tr class="item">
                <td></td>
                <td>
                    Ongkir:
                    Rp{{ number_format($transaction->shipping_cost, 0, ',', '.') }}
                </td>
            </tr>

            @if ($transaction->promo_code)
                <tr class="item">
                    <td></td>
                    <td style="color: green;">
                        Diskon ({{ $transaction->promo_code }}):
                        -Rp{{ number_format($transaction->discount_amount, 0, ',', '.') }}
                    </td>
                </tr>
            @endif

            <tr class="total">
                <td></td>
                <td>
                    Total: Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}
                </td>
            </tr>
        </table>

        <div class="text-center mt-8 no-print">
            <button onclick="window.print()"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Cetak Invoice
            </button>
            <button onclick="window.close()"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
                Tutup
            </button>
        </div>
    </div>
</body>

</html>
