<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOrWadekMiddleware
{
    /**
     * Handle an incoming request - Admin or Wadek III
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $allowedRoles = ['super_admin', 'admin', 'wadek_iii'];
        $userRole = auth()->user()->role->name;

        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Akses ditolak. Hanya Super Administrator, Administrator dan Wadek III yang diizinkan.');
        }

        return $next($request);
    }
}
