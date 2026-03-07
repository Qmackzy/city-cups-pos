<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{

public function index()
{
    // Mengambil semua shift yang sudah ditutup, diurutkan dari yang terbaru
    $shifts = \App\Models\Shift::with('user')
                ->where('status', 'closed')
                ->latest()
                ->paginate(10);

    return view('owner.shifts.index', compact('shifts'));
}
    /**
     * Menampilkan form Buka Shift (Mulai Kerja)
     */
    public function create()
    {
        // Jika sudah ada shift yang open, langsung arahkan ke transaksi
        $existingShift = Shift::where('user_id', Auth::id())
                              ->where('status', 'open')
                              ->first();

        if ($existingShift) {
            return redirect()->route('kasir.transaksi');
        }

        return view('kasir.shift.create');
    }

    /**
     * Proses menyimpan data Buka Shift
     */
    public function store(Request $request)
    {
        $request->validate([
            'starting_cash' => 'required|numeric|min:0'
        ]);

        Shift::create([
            'user_id' => Auth::id(),
            'start_time' => now(),
            'starting_cash' => $request->starting_cash,
            'status' => 'open'
        ]);

        return redirect()->route('kasir.transaksi')->with('success', 'Shift berhasil dibuka. Selamat bekerja!');
    }

    /**
     * Menampilkan form Tutup Shift (Z-Report)
     */
    public function close()
    {
        $shift = Shift::where('user_id', Auth::id())
                      ->where('status', 'open')
                      ->firstOrFail();

        // Hitung total penjualan CASH sejak shift dimulai
        $totalCashSales = Transaction::where('user_id', Auth::id())
                            ->where('payment_method', 'cash')
                            ->where('created_at', '>=', $shift->start_time)
                            ->sum('total_price');

        // Uang yang seharusnya ada di laci (Modal Awal + Total Penjualan Tunai)
        $expectedCash = $shift->starting_cash + $totalCashSales;

        return view('kasir.shift.close', compact('shift', 'totalCashSales', 'expectedCash'));
    }

    /**
     * Proses menyimpan data Tutup Shift & hitung selisih
     */
    public function update(Request $request)
    {
        $request->validate([
            'total_cash_actual' => 'required|numeric|min:0'
        ]);

        $shift = Shift::where('user_id', Auth::id())
                      ->where('status', 'open')
                      ->firstOrFail();

        // Hitung ulang ekspektasi untuk validasi akhir
        $totalCashSales = Transaction::where('user_id', Auth::id())
                            ->where('payment_method', 'cash')
                            ->where('created_at', '>=', $shift->start_time)
                            ->sum('total_price');

        $expectedCash = $shift->starting_cash + $totalCashSales;
        $actualCash = $request->total_cash_actual;

        $shift->update([
            'end_time' => now(),
            'total_cash_expected' => $expectedCash,
            'total_cash_actual' => $actualCash,
            'difference' => $actualCash - $expectedCash,
            'status' => 'closed'
        ]);

        // Opsional: Logout setelah tutup shift demi keamanan
        // Auth::logout(); 

        return redirect()->route('dashboard')->with('success', 'Shift ditutup. Selisih kasir: Rp ' . number_format($shift->difference, 0, ',', '.'));
    }
}