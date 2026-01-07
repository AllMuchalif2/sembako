<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    @vite(['resources/css/app.css'])
    <style>
        /* Pengaturan ukuran kertas untuk print */
        @page {
            size: A4 landscape;
            margin: 15mm;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }

            /* Optimasi untuk print */
            .report-box {
                box-shadow: none;
                border: none;
                margin: 0;
                padding: 0;
            }
        }

        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .report-box {
            max-width: 100%;
            margin: auto;
            padding: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f3f4f6;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
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
    <div class="report-box bg-white">
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
                            LAPORAN PENJUALAN
                        </div>
                        <div style="font-size: 11px; color: #666;">
                            <strong>Periode:</strong>
                            @if (request('start_date') && request('end_date'))
                                {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }} -
                                {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
                            @elseif(request('start_date'))
                                Sejak {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }}
                            @elseif(request('end_date'))
                                Sampai {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
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

        {{-- Product Table --}}
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 30%;">Nama Produk</th>
                    <th style="width: 15%;">Kategori</th>
                    <th class="text-right" style="width: 10%;">Terjual</th>
                    <th class="text-right" style="width: 15%;">Harga Jual</th>
                    <th class="text-right" style="width: 15%;">Pendapatan</th>
                    <th class="text-right" style="width: 15%;">Keuntungan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productReports as $report)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="font-bold">
                            {{ $report['name'] }}
                        </td>
                        <td>{{ $report['category'] }}</td>
                        <td class="text-right font-bold">{{ number_format($report['total_sold']) }}</td>
                        <td class="text-right">Rp {{ number_format($report['price'], 0, ',', '.') }}</td>
                        <td class="text-right font-bold">Rp
                            {{ number_format($report['total_revenue'], 0, ',', '.') }}</td>
                        <td class="text-right font-bold">Rp
                            {{ number_format($report['total_profit'], 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data produk ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="font-weight: bold;">
                    <td colspan="3" class="text-right">TOTAL</td>
                    <td class="text-right">{{ number_format($totalProductsSold) }}</td>
                    <td class="text-right"></td>
                    <td class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($totalProfit, 0, ',', '.') }}</td>
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
    </div>
</body>

</html>
