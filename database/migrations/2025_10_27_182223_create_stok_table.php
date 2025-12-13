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
        Schema::create('stok', function (Blueprint $table) {
            $table->id('id_stok');
            $table->unsignedBigInteger('id_produk');
            $table->decimal('jumlah', 10, 2)->default(0); // Ubah ke decimal untuk support desimal
            $table->enum('satuan', ['kg', 'gram'])->default('kg'); // Tambah kolom satuan
            $table->timestamp('update_at')->useCurrent()->useCurrentOnUpdate();

            // Foreign key constraint
            $table->foreign('id_produk')
                  ->references('id_produk')
                  ->on('produk')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok');
    }
};