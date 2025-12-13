<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'detail_transaksi';
    
    // Primary key
    protected $primaryKey = 'id_detail';
    
    // Nonaktifkan timestamps
    public $timestamps = false;

    // Field yang bisa diisi
    protected $fillable = [
        'id_transaksi',
        'id_varian',
        'jumlah',
        'subtotal',
    ];

    // Cast tipe data
    protected $casts = [
        'jumlah' => 'integer',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Relasi ke tabel transaksi
     * Satu detail belongs to satu transaksi
     */
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }

    /**
     * Relasi ke tabel varian_produk
     * Satu detail belongs to satu varian
     */
    public function varian()
    {
        return $this->belongsTo(VarianProduk::class, 'id_varian', 'id_varian');
    }

    /**
     * Helper method untuk mendapatkan nama produk
     */
    public function getNamaProdukAttribute()
    {
        return $this->varian && $this->varian->produk 
            ? $this->varian->produk->nama_produk 
            : '-';
    }

    /**
     * Helper method untuk mendapatkan berat varian
     */
    public function getBeratVarianAttribute()
    {
        return $this->varian ? $this->varian->berat : 0;
    }
}