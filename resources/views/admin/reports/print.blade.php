<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Transaksi - {{ config('app.name', 'MyMart') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18pt;
        }

        .header p {
            margin: 5px 0;
            font-size: 10pt;
            color: #555;
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

        .meta-info {
            margin-bottom: 20px;
        }

        @media print {
            .no-print {
                display: none;
            }

            @page {
                margin: 1cm;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="header">
        <h1>Laporan Pendapatan Transaksi Selesai</h1>
        <p>{{ config('app.name', 'MyMart') }}</p>
    </div>

    <div class="meta-info">
        <p><strong>Dicetak Tanggal:</strong> {{ now()->locale('id')->translatedFormat('d F Y H:i') }}</p>
        <p><strong>Periode:</strong>
            @if (request('start_date') && request('end_date'))
                {{ \Carbon\Carbon::parse(request('start_date'))->locale('id')->translatedFormat('d M Y') }} s/d
                {{ \Carbon\Carbon::parse(request('end_date'))->locale('id')->translatedFormat('d M Y') }}
            @elseif(request('start_date'))
                Sejak {{ \Carbon\Carbon::parse(request('start_date'))->locale('id')->translatedFormat('d M Y') }}
            @elseif(request('end_date'))
                Sampai {{ \Carbon\Carbon::parse(request('end_date'))->locale('id')->translatedFormat('d M Y') }}
            @else
                Semua Waktu
            @endif
        </p>
        <p><strong>Total Pendapatan:</strong> Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Tanggal</th>
                <th style="width: 25%;">ID Pesanan</th>
                <th style="width: 25%;">Pelanggan</th>
                <th style="width: 25%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $transaction->created_at->locale('id')->translatedFormat('d/m/Y H:i') }}</td>
                    <td>{{ $transaction->order_id }}</td>
                    <td>{{ $transaction->user->name ?? 'User Terhapus' }}</td>
                    <td class="text-right">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data transaksi.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right">Total</td>
                <td class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

</body>

</html>
