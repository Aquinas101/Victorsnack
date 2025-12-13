<?php

namespace App\Http\Controllers;

use App\Models\VarianProduk;
use App\Models\Produk;
use Illuminate\Http\Request;

class VarianProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $varians = VarianProduk::with('produk')->latest('id_varian')->paginate(10);
        return view('varian.index', compact('varians'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produks = Produk::orderBy('nama_produk', 'asc')->get();
        return view('varian.create', compact('produks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produk,id_produk',
            'berat' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
        ], [
            'id_produk.required' => 'Produk harus dipilih',
            'id_produk.exists' => 'Produk tidak ditemukan',
            'berat.required' => 'Berat harus diisi',
            'berat.integer' => 'Berat harus berupa angka',
            'berat.min' => 'Berat minimal 1 gram',
            'harga.required' => 'Harga harus diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga minimal 0',
        ]);

        try {
            VarianProduk::create([
                'id_produk' => $request->id_produk,
                'berat' => $request->berat,
                'harga' => $request->harga,
            ]);

            return redirect()->route(role_route('varian.index'))
                ->with('success', 'Varian produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan varian produk: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(VarianProduk $varian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $varian = VarianProduk::findOrFail($id);
        $produks = Produk::orderBy('nama_produk', 'asc')->get();
        return view('varian.edit', compact('varian', 'produks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $varian = VarianProduk::findOrFail($id);

        $request->validate([
            'id_produk' => 'required|exists:produk,id_produk',
            'berat' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
        ], [
            'id_produk.required' => 'Produk harus dipilih',
            'id_produk.exists' => 'Produk tidak ditemukan',
            'berat.required' => 'Berat harus diisi',
            'berat.integer' => 'Berat harus berupa angka',
            'berat.min' => 'Berat minimal 1 gram',
            'harga.required' => 'Harga harus diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga minimal 0',
        ]);

        try {
            $varian->update([
                'id_produk' => $request->id_produk,
                'berat' => $request->berat,
                'harga' => $request->harga,
            ]);

            return redirect()->route(role_route('varian.index'))
                ->with('success', 'Varian produk berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui varian produk: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $varian = VarianProduk::findOrFail($id);
            $varian->delete();

            return redirect()->route(role_route('varian.index'))
                ->with('success', 'Varian produk berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus varian produk: ' . $e->getMessage());
        }
    }
}