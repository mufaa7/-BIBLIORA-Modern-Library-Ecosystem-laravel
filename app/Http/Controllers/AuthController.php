<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Tampilkan Halaman Login Premium Lu
    public function showLogin()
    {
        return view('auth.login'); // Membaca file auth/login.blade.php
    }

    // 2. Proses Validasi Kredensial Masuk & Distribusi Jalur Sesuai Role
    public function processLogin(Request $request)
    {
        // Validasi input data dari form
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Coba autentikasi menggunakan data username dan password
        if (Auth::attempt($credentials)) {
            
            // Regenerasi session sukses login agar aman dari session fixation
            $request->session()->regenerate();
            
            // AMBIL DATA USER YANG BARU SAJA LOGGED IN
            $user = Auth::user();

            // SINKRONISASI JALUR AMAN: Cek toleran terhadap kolom 'role' atau 'level'
            $userRole = $user->role ?? $user->level ?? 'user';

            if ($userRole === 'admin') {
                // Paksa bypass memori intended, langsung tembak lurus ke Overview Utama (/)
                return redirect('/')->with('success', 'Authentication successful. Welcome to BIBLIORA, Chief Admin!');
            } else {
                // Jika user reguler/anggota biasa, oper ke halaman coming soon khusus mereka
                return redirect('/user/dashboard')->with('success', 'Welcome to BIBLIORA Ecosystem!');
            }
        }

        // Jika salah password/username dari awal
        return back()->withErrors([
            'username' => 'The provided credentials do not match our record matrices.',
        ])->onlyInput('username');
    }

    // 3. Proses Penghancuran Sesi (Logout)
    public function logout(Request $request)
    {
        // 1. Hancurkan autentikasi login user/admin
        Auth::logout();

        // 2. Bersihkan dan hancurkan session token di server
        $request->session()->invalidate();

        // 3. Amankan aplikasi dengan me-regenerate CSRF token baru
        $request->session()->regenerateToken();

        // 4. Lempar kembali ke gerbang login mode terang lu
        return redirect('/login')->with('success', 'Session ended successfully.');
    }
}