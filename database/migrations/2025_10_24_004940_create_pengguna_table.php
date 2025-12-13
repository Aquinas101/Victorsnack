<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengguna', function (Blueprint $table) {
            $table->id('id_pengguna');
            $table->string('nama_lengkap', 100);
            $table->date('tanggal_lahir')->nullable();
            $table->string('tempat_lahir', 50)->nullable();
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->enum('role', ['pemilik', 'karyawan', 'kasir']); // Ubah role
            $table->timestamp('create_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};