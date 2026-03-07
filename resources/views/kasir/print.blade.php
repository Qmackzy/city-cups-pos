<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Struk - {{ $transaction->invoice_number }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 300px; /* Standar printer thermal */
            margin: auto;
            padding: 10px;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .line { border-bottom: 1px dashed #000; margin: 10px 0; }
        .items-table { width: 100%; border-collapse: collapse; }
        .items-table td { padding: 3px 0; font-size: 14px; }
        .total-section td { font-weight: bold; }
        .footer { margin-top: 20px; font-size: 12px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px;">Cetak Struk</button>
        <a href="{{ route('kasir.transaksi') }}" style="padding: 10px 20px; text-decoration: none; background: #eee; color: #000; border: 1px solid #ccc;">Kembali</a>
    </div>

    <div class="text-center">
        <h2 style="margin: 0;">City Cups Coffee.Space</h2>
        <p style="font-size: 12px; margin: 5px 0;">Jl. xxxxx No. 123<br>Telp: xxxx-xxxx-xxxx</p>
    </div>

    <div class="line"></div>

    <table style="width: 100%; font-size: 12px;">
        <tr>
            <td>No: {{ $transaction->invoice_number }}</td>
            <td class="text-right">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td>Kasir: {{ $transaction->user->name }}</td>
            <td class="text-right" style="text-transform: uppercase;"><strong>{{ $transaction->payment_method }}</strong></td>
        </tr>
    </table>

    <div class="line"></div>

    <table class="items-table">
        @foreach($transaction->details as $detail)
        <tr>
            <td colspan="2">{{ $detail->product->name }}</td>
        </tr>
        <tr>
            <td style="text-indent: 10px;">{{ $detail->qty }} x {{ number_format($detail->price) }}</td>
            <td class="text-right">{{ number_format($detail->subtotal) }}</td>
        </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <table class="items-table total-section">
        <tr>
            <td>TOTAL</td>
            <td class="text-right">Rp {{ number_format($transaction->total_price) }}</td>
        </tr>
        <tr>
            <td>BAYAR ({{ strtoupper($transaction->payment_method) }})</td>
            <td class="text-right">Rp {{ number_format($transaction->pay_amount) }}</td>
        </tr>
        <tr>
            <td>KEMBALI</td>
            <td class="text-right">Rp {{ number_format($transaction->change_amount) }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="text-center footer">
        <p>Terima Kasih Atas Kunjungan Anda</p>
        <p>Barang yang