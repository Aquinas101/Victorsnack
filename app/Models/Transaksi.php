<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';
    public $timestamps = false;

    const CREATED_AT = 'tanggal_transaksi';

    protected $fillable = [
        'order_id_midtrans',
        'id_pengguna',
        'total_harga',
        'metode_pembayaran',
        'status_transaksi',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'total_harga' => 'decimal:2',
    ];

    /**
     * Relationship ke Pengguna (MANY TO ONE)
     */
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    /**
     * Relationship ke DetailTransaksi (ONE TO MANY)
     */
    public function details()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }

    /**
     * Alias untuk details (untuk backward compatibility)
     */
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }

    /**
     * Accessor untuk mendapatkan total item dalam transaksi
     */
    public function getTotalItemAttribute()
    {
        return $this->details()->sum('jumlah');
    }

    /**
     * Scope untuk transaksi hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('tanggal_transaksi', today());
    }

    /**
     * Scope untuk transaksi berhasil
     */
    public function scopeSuccess($query)
    {
        return $query->where('status_transaksi', 'berhasil');
    }

    /**
     * Scope untuk transaksi by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('id_pengguna', $userId);
    }
}