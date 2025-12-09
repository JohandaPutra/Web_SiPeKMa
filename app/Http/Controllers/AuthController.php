<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard-analytics');
        }

        // Get all users for easy testing
        $users = User::with(['role', 'prodi'])->get();

        return view('auth.login-test', compact('users'));
    }

    /**
     * Process login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            return redirect()->route('dashboard-analytics')
                ->with('success', "Selamat datang, {$user->username}! Role: {$user->role->display_name}");
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    /**
     * Quick login for testing (bypass password)
     */
    public function quickLogin(Request $request)
    {
        $user = User::find($request->user_id);

        if ($user) {
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('dashboard-analytics')
                ->with('success', "Login sebagai: {$user->username} ({$user->role->display_name})");
        }

        return back()->with('error', 'User tidak ditemukan');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout');
    }
}
