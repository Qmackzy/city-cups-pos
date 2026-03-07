<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::latest()->paginate(10);
        return view('owner.expenses.index', compact('expenses'));
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'amount' => 'required|numeric',
        'date' => 'required|date',
        'note' => 'nullable|string',
    ]);

    // Hanya simpan data yang sudah divalidasi (tanpa _token)
    Expense::create($data); 
    
    return back()->with('success', 'Pengeluaran berhasil dicatat!');
}

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return back()->with('success', 'Catatan pengeluaran dihapus!');
    }
}
