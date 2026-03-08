<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'kasir')->get();
        return view('owner.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'kasir',
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Akun Kasir berhasil dibuat!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Logika mengaktifkan/nonaktifkan lewat checkbox
        $user->is_active = $request->has('is_active') ? 1 : 0;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Data Kasir berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Cek riwayat transaksi (opsional tapi disarankan)
        $hasTransactions = \App\Models\Transaction::where('user_id', $id)->exists();

        if ($hasTransactions) {
            $user->update(['is_active' => false]);
            return redirect()->back()->with('success', 'Kasir memiliki riwayat transaksi, status diubah menjadi Nonaktif.');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Akun Kasir dihapus permanen.');
    }
}
