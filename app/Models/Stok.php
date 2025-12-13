<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'stok';
    
    // Primary key
    protected $primaryKey = 'id_stok';
    
    // Nonaktifkan timestamps default Laravel
    public $timestamps = false;

    // Field yang bisa diisi
    protected $fillable = [
        'id_produk',
        'jumlah',
        'satuan',
    ];

    // Cast tipe data
    protected $casts = [
        'jumlah' => 'decimal:2', // Support 2 digit desimal
        'update_at' => 'datetime',
    ];

    /**
     * Boot method untuk auto update timestamp
     */
    protected static function boot()
    {
        parent::boot();

        // Saat membuat data baru
        static::creating(function ($model) {
            $model->update_at = now();
            // Set default satuan jika belum ada
            if (empty($model->satuan)) {
                $model->satuan = 'kg';
            }
        });

        // Saat update data
        static::updating(function ($model) {
            $model->update_at = now();
        });
    }

    /**
     * Relasi ke tabel produk
     * Satu stok belongs to satu produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    /* ========================================
     * KONVERSI SATUAN METHODS (GRAM ↔ KG/GRAM)
     * ======================================== */

    /**
     * Konversi gram ke satuan stok (kg atau gram)
     * Digunakan saat proses transaksi untuk menghitung pengurangan stok
     * 
     * @param int|float $jumlahDalamGram Jumlah dalam gram (dari berat varian)
     * @return float Jumlah dalam satuan stok
     * 
     * Contoh:
     * - Stok dalam kg, input 500g → return 0.5 kg
     * - Stok dalam gram, input 500g → return 500 gram
     */
    public function konversiKeStok($jumlahDalamGram)
    {
        if ($this->satuan === 'kg') {
            // Konversi gram ke kilogram
            return $jumlahDalamGram / 1000;
        }
        
        // Sudah dalam gram, tidak perlu konversi
        return $jumlahDalamGram;
    }

    /**
     * Get stok dalam gram (untuk display atau perhitungan)
     * 
     * @return float Jumlah stok dalam gram
     * 
     * Contoh:
     * - Stok 1.5 kg → return 1500 gram
     * - Stok 500 gram → return 500 gram
     */
    public function getStokDalamGram()
    {
        if ($this->satuan === 'kg') {
            return $this->jumlah * 1000;
        }
        
        return $this->jumlah;
    }

    /* ========================================
     * STOK MANAGEMENT METHODS (UPDATED)
     * ======================================== */

    /**
     * Cek apakah stok mencukupi untuk jumlah tertentu (dalam gram)
     * Method ini sudah di-update untuk support konversi satuan
     * 
     * @param int|float $jumlahDalamGram Jumlah yang dibutuhkan dalam gram (total varian × qty)
     * @return bool True jika stok mencukupi
     * 
     * Contoh penggunaan:
     * - Varian 250g, qty 2 → $jumlahDalamGram = 500g
     * - Stok 1kg → cek: 1kg >= 0.5kg → TRUE ✓
     */
    public function cekStokTersedia($jumlahDalamGram)
    {
        $jumlahDibutuhkan = $this->konversiKeStok($jumlahDalamGram);
        return $this->jumlah >= $jumlahDibutuhkan;
    }

    /**
     * Kurangi stok berdasarkan jumlah dalam gram
     * Method ini sudah di-update untuk support konversi satuan
     * 
     * @param int|float $jumlahDalamGram Jumlah yang akan dikurangi dalam gram (total varian × qty)
     * @return bool True jika berhasil
     * @throws \Exception Jika stok tidak mencukupi
     * 
     * Contoh penggunaan:
     * - Varian 250g, qty 2 → $jumlahDalamGram = 500g
     * - Stok 1kg → kurangi 0.5kg → sisa 0.5kg ✓
     */
    public function kurangiStok($jumlahDalamGram)
    {
        // Konversi jumlah ke satuan stok
        $jumlahDikurangi = $this->konversiKeStok($jumlahDalamGram);
        
        // Validasi stok mencukupi
        if ($this->jumlah < $jumlahDikurangi) {
            throw new \Exception(
                "Stok tidak mencukupi! Stok tersedia: " . 
                number_format($this->jumlah, 2, ',', '.') . " {$this->satuan}, " .
                "yang dibutuhkan: " . 
                number_format($jumlahDikurangi, 2, ',', '.') . " {$this->satuan}"
            );
        }
        
        // Kurangi stok
        $this->jumlah -= $jumlahDikurangi;
        $this->save();
        
        return true;
    }

    /**
     * Tambah stok berdasarkan jumlah dalam gram
     * Method ini sudah di-update untuk support konversi satuan
     * 
     * @param int|float $jumlahDalamGram Jumlah yang akan ditambahkan dalam gram
     * @return bool True jika berhasil
     * 
     * Contoh penggunaan:
     * - Input 1000g → Stok dalam kg: tambah 1kg
     * - Input 1000g → Stok dalam gram: tambah 1000g
     */
    public function tambahStok($jumlahDalamGram)
    {
        // Konversi jumlah ke satuan stok
        $jumlahDitambah = $this->konversiKeStok($jumlahDalamGram);
        
        // Tambah stok
        $this->jumlah += $jumlahDitambah;
        $this->save();
        
        return true;
    }

    /* ========================================
     * DISPLAY / FORMATTING METHODS
     * ======================================== */

    /**
     * Format jumlah dengan satuan
     * 
     * @return string Format: "10.50 kg" atau "500.00 gram"
     */
    public function getJumlahFormatted()
    {
        return number_format($this->jumlah, 2, ',', '.') . ' ' . $this->satuan;
    }

    /**
     * Alias untuk getJumlahFormatted() - untuk consistency
     * 
     * @return string Format stok dengan satuan
     */
    public function getStokFormatted()
    {
        return $this->getJumlahFormatted();
    }
}