<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { bg-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .total { font-weight: bold; text-align: right; margin-top: 20px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PENJUALAN CITY CUPS </h2>
        <p>Periode: {{ $startDate }} s/d {{ $endDate }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Total Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $trx)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $trx->user->name }}</td>
                <td>Rp {{ number_format($trx->total_price) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        TOTAL PENDAPATAN: Rp {{ number_format($totalEarnings) }}
    </div>
</body>
</html>