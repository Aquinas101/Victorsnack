<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penggunas = [
            // PEMILIK (Admin dengan akses penuh)
            [
                'nama_lengkap' => 'Victor Pemilik',
                'tanggal_lahir' => '1980-01-15',
                'tempat_lahir' => 'Jakarta',
                'username' => 'pemilik',
                'password' => Hash::make('password123'),
                'role' => 'pemilik',
                'create_at' => Carbon::now(),
            ],
            
            // KARYAWAN 1 (Kelola produk, stok, varian)
            [
                'nama_lengkap' => 'Budi Karyawan',
                'tanggal_lahir' => '1995-08-10',
                'tempat_lahir' => 'Surabaya',
                'username' => 'budi',
                'password' => Hash::make('password123'),
                'role' => 'karyawan',
                'create_at' => Carbon::now(),
            ],
            
            // KARYAWAN 2 (Kelola produk, stok, varian)
            [
                'nama_lengkap' => 'Andi Karyawan',
                'tanggal_lahir' => '1992-11-05',
                'tempat_lahir' => 'Bandung',
                'username' => 'andi',
                'password' => Hash::make('password123'),
                'role' => 'karyawan',
                'create_at' => Carbon::now(),
            ],
            
            // KASIR 1 (Hanya transaksi)
            [
                'nama_lengkap' => 'Siti Kasir',
                'tanggal_lahir' => '1998-03-25',
                'tempat_lahir' => 'Yogyakarta',
                'username' => 'siti',
                'password' => Hash::make('password123'),
                'role' => 'kasir',
                'create_at' => Carbon::now(),
            ],
            
            // KASIR 2 (Hanya transaksi)
            [
                'nama_lengkap' => 'Dewi Kasir',
                'tanggal_lahir' => '1999-07-18',
                'tempat_lahir' => 'Malang',
                'username' => 'dewi',
                'password' => Hash::make('password123'),
                'role' => 'kasir',
                'create_at' => Carbon::now(),
            ],
        ];

        DB::table('pengguna')->insert($penggunas);
    }
}