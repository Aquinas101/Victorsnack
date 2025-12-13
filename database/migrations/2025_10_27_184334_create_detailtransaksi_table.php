<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_transaksi');
            $table->unsignedBigInteger('id_varian');
            $table->integer('jumlah');
            $table->decimal('subtotal', 10, 2);

            $table->foreign('id_transaksi')
                  ->references('id_transaksi')
                  ->on('transaksi')
                  ->onDelete('cascade');

            $table->foreign('id_varian')
                  ->references('id_varian')
                  ->on('varian_produk')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
    }
};