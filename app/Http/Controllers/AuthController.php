<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class AuthController extends Controller
{
    /**
     * Tampilkan form login
     */
    public function showLogin()
    {
        // Jika sudah login, redirect ke dashboard sesuai role
        if (Auth::check()) {
            return $this->redirectToDashboard();
        }

        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username harus diisi',
            'password.required' => 'Password harus diisi',
        ]);

        // Cari pengguna berdasarkan username
        $pengguna = Pengguna::where('username', $request->username)->first();

        // Cek apakah pengguna ditemukan dan password cocok
        if (!$pengguna || !Hash::check($request->password, $pengguna->password)) {
            return back()->withErrors([
                'login' => 'Username atau password salah!',
            ])->withInput($request->only('username'));
        }

        // Login pengguna
        Auth::login($pengguna);

        // Regenerate session untuk keamanan
        $request->session()->regenerate();

        // Simpan informasi user ke session
        session([
            'user_role' => $pengguna->role,
            'user_name' => $pengguna->nama_lengkap,
            'user_id' => $pengguna->id_pengguna,
        ]);

        // Redirect ke dashboard sesuai role
        return $this->redirectToDashboard();
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda berhasil logout!');
    }

    /**
     * Redirect ke dashboard sesuai role
     */
    private function redirectToDashboard()
    {
        $user = Auth::user();

        // Pemilik memiliki akses penuh sebagai admin
        if ($user->role === 'pemilik') {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Selamat datang, ' . $user->nama_lengkap . '!');
        } 
        // Karyawan memiliki akses terbatas (kelola stok, produk, varian)
        elseif ($user->role === 'karyawan') {
            return redirect()->route('karyawan.dashboard')
                ->with('success', 'Selamat datang, ' . $user->nama_lengkap . '!');
        } 
        // Kasir hanya bisa melakukan transaksi
        elseif ($user->role === 'kasir') {
            return redirect()->route('kasir.dashboard')
                ->with('success', 'Selamat datang, ' . $user->nama_lengkap . '!');
        }

        // Fallback jika role tidak dikenali
        Auth::logout();
        return redirect('/login')->withErrors(['login' => 'Role tidak valid!']);
    }
}