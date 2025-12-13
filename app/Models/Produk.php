<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    public $timestamps = false;

    // Nama kolom timestamp custom
    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    protected $fillable = [
        'nama_produk',
        'kategori',
        'gambar',
    ];

    protected $casts = [
        'create_at' => 'datetime',
        'update_at' => 'datetime',
    ];

    /**
     * Relationship ke VarianProduk (ONE TO MANY)
     * Satu produk memiliki banyak varian
     */
    public function varians()
    {
        return $this->hasMany(VarianProduk::class, 'id_produk', 'id_produk');
    }

    /**
     * Alias untuk varians (untuk backward compatibility)
     */
    public function varian()
    {
        return $this->hasMany(VarianProduk::class, 'id_produk', 'id_produk');
    }

    /**
     * Relationship ke Stok (ONE TO ONE)
     * Satu produk memiliki satu stok
     */
    public function stok()
    {
        return $this->hasOne(Stok::class, 'id_produk', 'id_produk');
    }

    /**
     * Accessor untuk mendapatkan total stok
     */
    public function getTotalStokAttribute()
    {
        return $this->stok ? $this->stok->jumlah : 0;
    }

    /**
     * Scope untuk produk dengan stok tersedia
     */
    public function scopeWithStock($query)
    {
        return $query->whereHas('stok', function($q) {
            $q->where('jumlah', '>', 0);
        });
    }

    /**
     * Scope untuk produk dengan stok menipis
     */
    public function scopeLowStock($query, $threshold = 5)
    {
        return $query->whereHas('stok', function($q) use ($threshold) {
            $q->where('jumlah', '<', $threshold);
        });
    }
}