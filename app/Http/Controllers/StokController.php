<?php

namespace App\Http\Controllers;

use App\Models\Stok;
use App\Models\Produk;
use Illuminate\Http\Request;

class StokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stoks = Stok::with('produk')->latest('update_at')->paginate(10);
        return view('stok.index', compact('stoks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil produk yang belum ada stoknya
        $produksWithStok = Stok::pluck('id_produk')->toArray();
        $produks = Produk::whereNotIn('id_produk', $produksWithStok)
                         ->orderBy('nama_produk', 'asc')
                         ->get();
        
        return view('stok.create', compact('produks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produk,id_produk|unique:stok,id_produk',
            'jumlah' => 'required|numeric|min:0',
            'satuan' => 'required|in:kg,gram',
        ], [
            'id_produk.required' => 'Produk harus dipilih',
            'id_produk.exists' => 'Produk tidak ditemukan',
            'id_produk.unique' => 'Stok untuk produk ini sudah ada',
            'jumlah.required' => 'Jumlah stok harus diisi',
            'jumlah.numeric' => 'Jumlah stok harus berupa angka',
            'jumlah.min' => 'Jumlah stok minimal 0',
            'satuan.required' => 'Satuan harus dipilih',
            'satuan.in' => 'Satuan tidak valid',
        ]);

        try {
            Stok::create([
                'id_produk' => $request->id_produk,
                'jumlah' => $request->jumlah,
                'satuan' => $request->satuan,
            ]);

            return redirect()->route(role_route('stok.index'))
                ->with('success', 'Stok barang berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan stok: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Stok $stok)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $stok = Stok::with('produk')->findOrFail($id);
        return view('stok.edit', compact('stok'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $stok = Stok::findOrFail($id);

        $request->validate([
            'jumlah' => 'required|numeric|min:0',
            'tipe' => 'required|in:set,tambah,kurang',
        ], [
            'jumlah.required' => 'Jumlah harus diisi',
            'jumlah.numeric' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah minimal 0',
            'tipe.required' => 'Tipe operasi harus dipilih',
            'tipe.in' => 'Tipe operasi tidak valid',
        ]);

        try {
            $jumlah = $request->jumlah;
            
            // Set stok (replace)
            if ($request->tipe === 'set') {
                $stok->jumlah = $jumlah;
            }
            // Tambah stok
            elseif ($request->tipe === 'tambah') {
                $stok->jumlah += $jumlah;
            }
            // Kurangi stok
            elseif ($request->tipe === 'kurang') {
                if ($stok->jumlah < $jumlah) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . number_format($stok->jumlah, 2, ',', '.') . ' ' . $stok->satuan);
                }
                $stok->jumlah -= $jumlah;
            }

            $stok->save();

            return redirect()->route(role_route('stok.index'))
                ->with('success', 'Stok barang berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui stok: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $stok = Stok::findOrFail($id);
            $stok->delete();

            return redirect()->route(role_route('stok.index'))
                ->with('success', 'Stok barang berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus stok: ' . $e->getMessage());
        }
    }
}