<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->string('order_id_midtrans')->nullable();
            $table->unsignedBigInteger('id_pengguna');
            $table->decimal('total_harga', 10, 2);
            $table->decimal('uang_dibayar', 10, 2)->nullable();
            $table->decimal('kembalian', 10, 2)->nullable();
            $table->enum('metode_pembayaran', ['tunai', 'kredit', 'debit', 'dompet_digital']);
            $table->timestamp('tanggal_transaksi')->useCurrent();
            $table->enum('status_transaksi', ['berhasil', 'pending', 'gagal'])->default('pending');

            $table->foreign('id_pengguna')
                  ->references('id_pengguna')
                  ->on('pengguna')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};