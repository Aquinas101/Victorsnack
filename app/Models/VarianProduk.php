<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VarianProduk extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'varian_produk';
    
    // Primary key
    protected $primaryKey = 'id_varian';
    
    // Nonaktifkan timestamps default Laravel
    public $timestamps = false;

    // Field yang bisa diisi
    protected $fillable = [
        'id_produk',
        'berat',
        'harga',
    ];

    // Cast tipe data
    protected $casts = [
        'berat' => 'integer',
        'harga' => 'decimal:2',
    ];

    /**
     * Relasi ke tabel produk
     * Satu varian belongs to satu produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    /**
     * Relasi ke tabel detail_transaksi
     * Satu varian bisa ada di banyak detail transaksi
     */
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_varian', 'id_varian');
    }
}