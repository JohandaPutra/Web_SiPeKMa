<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request - Super Admin Only
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (auth()->user()->role->name !== 'admin') {
            abort(403, 'Akses ditolak. Hanya Super Administrator yang diizinkan.');
        }

        return $next($request);
    }
}
