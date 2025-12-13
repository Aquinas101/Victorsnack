<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';
    public $timestamps = false;

    const CREATED_AT = 'create_at';

    protected $fillable = [
        'nama_lengkap',
        'tanggal_lahir',
        'tempat_lahir',
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'create_at' => 'datetime',
    ];

    /**
     * Relationship ke Transaksi (ONE TO MANY)
     */
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_pengguna', 'id_pengguna');
    }

    /**
     * Check if user is pemilik
     */
    public function isPemilik()
    {
        return $this->role === 'pemilik';
    }

    /**
     * Check if user is karyawan
     */
    public function isKaryawan()
    {
        return $this->role === 'karyawan';
    }

    /**
     * Check if user is kasir
     */
    public function isKasir()
    {
        return $this->role === 'kasir';
    }

    /**
     * Get role display name
     */
    public function getRoleNameAttribute()
    {
        return ucfirst($this->role);
    }

    /**
     * Mutator untuk hash password otomatis
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Accessor untuk umur
     */
    public function getUmurAttribute()
    {
        if (!$this->tanggal_lahir) {
            return null;
        }
        return $this->tanggal_lahir->age;
    }
}