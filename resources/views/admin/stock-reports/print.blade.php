<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Stok - {{ config('app.name', 'MyMart') }}</title>
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

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-green {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-yellow {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-red {
            background-color: #fee2e2;
            color: #991b1b;
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

        .summary-box {
            display: inline-block;
            padding: 10px 15px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .summary-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }

        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #333;
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
                        LAPORAN STOK
                    </div>
                    <div style="font-size: 10px; color: #999; margin-top: 3px;">
                        Dicetak: {{ now()->locale('id')->translatedFormat('d M Y, H:i') }} WIB
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Summary Statistics --}}
    <div style="text-align: center; margin-bottom: 20px;">
        <div class="summary-box">
            <div class="summary-label">Total Produk</div>
            <div class="summary-value">{{ number_format($totalProducts) }}</div>
        </div>
        <div class="summary-box">
            <div class="summary-label">Total Nilai Stok</div>
            <div class="summary-value">Rp {{ number_format($totalStockValue, 0, ',', '.') }}</div>
        </div>
        <div class="summary-box">
            <div class="summary-label">Stok Rendah</div>
            <div class="summary-value">{{ number_format($lowStockCount) }}</div>
        </div>
        <div class="summary-box">
            <div class="summary-label">Stok Habis</div>
            <div class="summary-value">{{ number_format($outOfStockCount) }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">Produk</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 10%;">Stok</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 15%;">Harga Beli</th>
                <th style="width: 15%;">Nilai Stok</th>
                <th style="width: 10%;">Terjual</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stockReports as $report)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $report['name'] }}</td>
                    <td>{{ $report['category'] }}</td>
                    <td class="text-right">{{ number_format($report['stock']) }}</td>
                    <td class="text-center">
                        @if ($report['status'] == 'safe')
                            <span class="badge badge-green">{{ $report['status_label'] }}</span>
                        @elseif($report['status'] == 'low')
                            <span class="badge badge-yellow">{{ $report['status_label'] }}</span>
                        @else
                            <span class="badge badge-red">{{ $report['status_label'] }}</span>
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($report['buy_price'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($report['stock_value'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($report['total_sold']) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data produk.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Footer --}}
    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; font-size: 11px; color: #666;">
        <p style="margin: 5px 0;">
            <strong>Dicetak oleh:</strong> {{ $admin->name }}
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
