<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::latest('create_at')->paginate(10);
        return view('produk.index', compact('produks'));
    }

    public function create()
    {
        return view('produk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nama_produk.required' => 'Nama produk harus diisi',
            'nama_produk.max' => 'Nama produk maksimal 100 karakter',
            'kategori.required' => 'Kategori harus diisi',
            'kategori.max' => 'Kategori maksimal 50 karakter',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        try {
            $data = [
                'nama_produk' => $request->nama_produk,
                'kategori' => $request->kategori,
            ];

            // Upload gambar jika ada
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/produk'), $filename);
                $data['gambar'] = 'uploads/produk/' . $filename;
            }

            Produk::create($data);

            return redirect()->route(role_route('produk.index'))
                ->with('success', 'Produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('produk.edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'nama_produk' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nama_produk.required' => 'Nama produk harus diisi',
            'nama_produk.max' => 'Nama produk maksimal 100 karakter',
            'kategori.required' => 'Kategori harus diisi',
            'kategori.max' => 'Kategori maksimal 50 karakter',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        try {
            $data = [
                'nama_produk' => $request->nama_produk,
                'kategori' => $request->kategori,
            ];

            // Upload gambar baru jika ada
            if ($request->hasFile('gambar')) {
                // Hapus gambar lama jika ada
                if ($produk->gambar && file_exists(public_path($produk->gambar))) {
                    unlink(public_path($produk->gambar));
                }

                $file = $request->file('gambar');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/produk'), $filename);
                $data['gambar'] = 'uploads/produk/' . $filename;
            }

            $produk->update($data);

            return redirect()->route(role_route('produk.index'))
                ->with('success', 'Produk berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $produk = Produk::findOrFail($id);
            
            // Hapus gambar jika ada
            if ($produk->gambar && file_exists(public_path($produk->gambar))) {
                unlink(public_path($produk->gambar));
            }
            
            $produk->delete();

            return redirect()->route(role_route('produk.index'))
                ->with('success', 'Produk berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}