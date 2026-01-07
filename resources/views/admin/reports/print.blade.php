<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Pendapatan - {{ config('app.name', 'MyMart') }}</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    @vite(['resources/css/app.css'])
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            margin: 0;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row td {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        @media print {
            .no-print {
                display: none;
            }

            @page {
                margin: 1cm;
            }
        }

        /* Button Styles */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            text-transform: uppercase;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-print {
            background-color: #16a34a;
            color: white;
            margin-right: 10px;
        }

        .btn-print:hover {
            background-color: #15803d;
        }

        .btn-close {
            background-color: #6b7280;
            color: white;
        }

        .btn-close:hover {
            background-color: #4b5563;
        }

        .button-container {
            text-align: center;
            margin-top: 30px;
            padding: 20px 0;
        }
    </style>
</head>

<body onload="window.print()">

    {{-- Header --}}
    <div style="margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px;">
        <table style="border: none; width: 100%;">
            <tr style="border: none;">
                <td style="border: none; width: 80px; vertical-align: top;">
                    <img src="{{ asset('images/logo.png') }}" style="width: 70px;">
                </td>
                <td style="border: none; vertical-align: top;">
                    <div style="font-size: 18px; font-weight: bold; margin-bottom: 3px;">
                        {{ $settings->store_name }}
                    </div>
                    @if ($settings->store_address)
                        <div style="font-size: 11px; color: #666; line-height: 1.4;">
                            {{ $settings->store_address }}
                        </div>
                    @endif
                </td>
                <td style="border: none; text-align: right; vertical-align: top;">
                    <div style="font-size: 16px; font-weight: bold; margin-bottom: 5px;">
                        LAPORAN PENDAPATAN
                    </div>
                    <div style="font-size: 11px; color: #666;">
                        <strong>Periode:</strong>
                        @if (request('start_date') && request('end_date'))
                            {{ \Carbon\Carbon::parse(request('start_date'))->locale('id')->translatedFormat('d M Y') }}
                            -
                            {{ \Carbon\Carbon::parse(request('end_date'))->locale('id')->translatedFormat('d M Y') }}
                        @elseif(request('start_date'))
                            Sejak
                            {{ \Carbon\Carbon::parse(request('start_date'))->locale('id')->translatedFormat('d M Y') }}
                        @elseif(request('end_date'))
                            Sampai
                            {{ \Carbon\Carbon::parse(request('end_date'))->locale('id')->translatedFormat('d M Y') }}
                        @else
                            Semua Waktu
                        @endif
                    </div>
                    <div style="font-size: 10px; color: #999; margin-top: 3px;">
                        Dicetak: {{ now()->locale('id')->translatedFormat('d M Y, H:i') }} WIB
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 20%;">ID Pesanan</th>
                <th style="width: 20%;">Pelanggan</th>
                <th style="width: 15%;">Keuntungan</th>
                <th style="width: 10%;">Margin</th>
                <th style="width: 15%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $transaction->created_at->locale('id')->translatedFormat('d/m/Y H:i') }}</td>
                    <td>{{ $transaction->order_id }}</td>
                    <td>{{ $transaction->user->name ?? 'User Terhapus' }}</td>
                    <td class="text-right">
                        @php
                            $transactionProfit = 0;
                            foreach ($transaction->items as $item) {
                                if ($item->product && $item->product->buy_price) {
                                    $transactionProfit += ($item->price - $item->product->buy_price) * $item->quantity;
                                }
                            }
                        @endphp
                        Rp {{ number_format($transactionProfit, 0, ',', '.') }}
                    </td>
                    <td class="text-right">
                        @php
                            $transactionCost = $transaction->total_amount - $transactionProfit;
                            $transactionMargin =
                                $transactionCost > 0 ? ($transactionProfit / $transactionCost) * 100 : 0;
                        @endphp
                        {{ number_format($transactionMargin, 1) }}%
                    </td>
                    <td class="text-right">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data transaksi.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right">Total</td>
                <td class="text-right">Rp {{ number_format($totalProfit, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($marginPercentage, 1) }}%</td>
                <td class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- Footer --}}
    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; font-size: 11px; color: #666;">
        <p style="margin: 5px 0;">
            <strong>Dicetak oleh:</strong> {{ $admin->name }}
        </p>
        <p style="margin: 5px 0;">
            <strong>Tanggal cetak:</strong> {{ now()->locale('id')->translatedFormat('d F Y, H:i') }} WIB
        </p>
    </div>

    {{-- Print Button --}}
    <div class="button-container no-print">
        <button onclick="window.print()" type="button" class="btn btn-print">
            Cetak Laporan
        </button>
        <button onclick="window.close()" type="button" class="btn btn-close">
            Tutup
        </button>
    </div>

</body>

</html>
