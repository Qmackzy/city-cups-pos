<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    // Ubah nama function-nya menjadi ini
public function exportToPdf(Request $request) 
{
    $startDate = $request->start_date ?? date('Y-m-d');
    $endDate = $request->end_date ?? date('Y-m-d');

    $transactions = Transaction::with('user')
        ->whereBetween('created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"])
        ->get();

    $totalEarnings = $transactions->sum('total_price');

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('owner.reports.pdf', compact('transactions', 'totalEarnings', 'startDate', 'endDate'));
    
    return $pdf->stream("Laporan-Penjualan.pdf"); // Gunakan stream() agar terbuka di tab baru dulu
}

public function laporan(Request $request)
{
    // 1. Ambil tanggal dari input, default ke hari ini jika kosong
    $startDate = $request->query('start_date', date('Y-m-d'));
    $endDate = $request->query('end_date', date('Y-m-d'));

    // 2. Query transaksi berdasarkan filter tanggal
    // Gunakan whereBetween untuk rentang waktu (ditambah jam agar mencakup seluruh hari)
    $transactions = Transaction::with('user')
        ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
        ->orderBy('created_at', 'desc')
        ->get();

    // 3. Hitung Pendapatan Berdasarkan Filter
    $filteredReport = $transactions->sum('total_price');

    // 4. Hitung Pendapatan Total Bulan Ini (tanpa terpengaruh filter)
    $monthlyReport = Transaction::whereMonth('created_at', date('m'))
        ->whereYear('created_at', date('Y'))
        ->sum('total_price');

    // 5. Kirim data ke view
    return view('owner.laporan.index', compact(
        'transactions', 
        'startDate', 
        'endDate', 
        'filteredReport', 
        'monthlyReport'
    ));
}
}
