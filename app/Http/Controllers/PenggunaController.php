<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pengguna::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Search by nama atau username
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $penggunas = $query->latest('create_at')->paginate(10);

        return view('pengguna.index', compact('penggunas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pengguna.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'tanggal_lahir' => 'nullable|date|before:today',
            'tempat_lahir' => 'nullable|string|max:50',
            'username' => 'required|string|max:50|unique:pengguna,username',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:pemilik,karyawan,kasir',
        ], [
            'nama_lengkap.required' => 'Nama lengkap harus diisi',
            'nama_lengkap.max' => 'Nama lengkap maksimal 100 karakter',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini',
            'tempat_lahir.max' => 'Tempat lahir maksimal 50 karakter',
            'username.required' => 'Username harus diisi',
            'username.max' => 'Username maksimal 50 karakter',
            'username.unique' => 'Username sudah digunakan',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role harus dipilih',
            'role.in' => 'Role tidak valid',
        ]);

        try {
            Pengguna::create([
                'nama_lengkap' => $request->nama_lengkap,
                'tanggal_lahir' => $request->tanggal_lahir,
                'tempat_lahir' => $request->tempat_lahir,
                'username' => $request->username,
                'password' => $request->password, // Auto hash via mutator
                'role' => $request->role,
            ]);

            return redirect()->route(role_route('pengguna.index'))
                ->with('success', 'Pengguna berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan pengguna: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pengguna = Pengguna::with('transaksi')->findOrFail($id);
        return view('pengguna.show', compact('pengguna'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pengguna = Pengguna::findOrFail($id);
        
        // Tidak bisa edit diri sendiri
        if ($pengguna->id_pengguna === Auth::id()) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat mengedit akun sendiri!');
        }

        return view('pengguna.edit', compact('pengguna'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pengguna = Pengguna::findOrFail($id);

        // Tidak bisa edit diri sendiri
        if ($pengguna->id_pengguna === Auth::id()) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat mengedit akun sendiri!');
        }

        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'tanggal_lahir' => 'nullable|date|before:today',
            'tempat_lahir' => 'nullable|string|max:50',
            'username' => 'required|string|max:50|unique:pengguna,username,' . $id . ',id_pengguna',
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:pemilik,karyawan,kasir',
        ], [
            'nama_lengkap.required' => 'Nama lengkap harus diisi',
            'nama_lengkap.max' => 'Nama lengkap maksimal 100 karakter',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini',
            'tempat_lahir.max' => 'Tempat lahir maksimal 50 karakter',
            'username.required' => 'Username harus diisi',
            'username.max' => 'Username maksimal 50 karakter',
            'username.unique' => 'Username sudah digunakan',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role harus dipilih',
            'role.in' => 'Role tidak valid',
        ]);

        try {
            $data = [
                'nama_lengkap' => $request->nama_lengkap,
                'tanggal_lahir' => $request->tanggal_lahir,
                'tempat_lahir' => $request->tempat_lahir,
                'username' => $request->username,
                'role' => $request->role,
            ];

            // Update password hanya jika diisi (auto hash via mutator)
            if ($request->filled('password')) {
                $data['password'] = $request->password;
            }

            $pengguna->update($data);

            return redirect()->route(role_route('pengguna.index'))
                ->with('success', 'Pengguna berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui pengguna: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Tidak bisa hapus diri sendiri
        if ($id == Auth::id()) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        try {
            $pengguna = Pengguna::findOrFail($id);
            
            // Cek apakah pengguna memiliki transaksi
            if ($pengguna->transaksi()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus pengguna yang memiliki riwayat transaksi!');
            }

            $pengguna->delete();

            return redirect()->route(role_route('pengguna.index'))
                ->with('success', 'Pengguna berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }

    /**
     * Reset password pengguna
     */
    public function resetPassword(Request $request, $id)
    {
        $pengguna = Pengguna::findOrFail($id);

        $request->validate([
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'new_password.required' => 'Password baru harus diisi',
            'new_password.min' => 'Password minimal 6 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            // Auto hash via mutator
            $pengguna->update([
                'password' => $request->new_password,
            ]);

            return redirect()->back()
                ->with('success', 'Password berhasil direset!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal reset password: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan halaman profil user yang sedang login
     */
    public function profile()
    {
        $pengguna = Auth::user();
        return view('pengguna.profile', compact('pengguna'));
    }

    /**
     * Update profil user yang sedang login
     */
    public function updateProfile(Request $request)
    {
        $pengguna = Auth::user();
        
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'tanggal_lahir' => 'nullable|date|before:today',
            'tempat_lahir' => 'nullable|string|max:50',
            'username' => 'required|string|max:50|unique:pengguna,username,' . $pengguna->id_pengguna . ',id_pengguna',
        ], [
            'nama_lengkap.required' => 'Nama lengkap harus diisi',
            'nama_lengkap.max' => 'Nama lengkap maksimal 100 karakter',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini',
            'tempat_lahir.max' => 'Tempat lahir maksimal 50 karakter',
            'username.required' => 'Username harus diisi',
            'username.max' => 'Username maksimal 50 karakter',
            'username.unique' => 'Username sudah digunakan',
        ]);

        try {
            $pengguna->update([
                'nama_lengkap' => $request->nama_lengkap,
                'tanggal_lahir' => $request->tanggal_lahir,
                'tempat_lahir' => $request->tempat_lahir,
                'username' => $request->username,
            ]);

            return redirect()->back()
                ->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan halaman ganti password
     */
    public function changePassword()
    {
        return view('pengguna.change-password');
    }

    /**
     * Update password user yang sedang login
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Password lama harus diisi',
            'new_password.required' => 'Password baru harus diisi',
            'new_password.min' => 'Password baru minimal 6 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            $pengguna = Auth::user();

            // Cek apakah password lama benar
            if (!Hash::check($request->current_password, $pengguna->password)) {
                return redirect()->back()
                    ->with('error', 'Password lama tidak sesuai!');
            }

            // Update password (auto hash via mutator)
            $pengguna->update([
                'password' => $request->new_password,
            ]);

            return redirect()->back()
                ->with('success', 'Password berhasil diubah!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengubah password: ' . $e->getMessage());
        }
    }
}