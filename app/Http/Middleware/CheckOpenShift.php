<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOpenShift
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    // Cek apakah ada shift yang masih berstatus 'open' untuk user ini
    $openShift = \App\Models\Shift::where('user_id', auth()->id())
                                 ->where('status', 'open')
                                 ->exists();

    // Jika tidak ada shift DAN bukan sedang mengakses halaman buka shift, redirect!
    if (!$openShift && !$request->is('kasir/shift*')) {
        return redirect()->route('kasir.shift.create')
                         ->with('error', 'Anda harus membuka shift terlebih dahulu!');
    }

    return $next($request); // Perbaikan di sini
}
}
