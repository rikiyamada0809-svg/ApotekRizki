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
        Schema::create('obats', function (Blueprint $table) {
            $table->id();
            $table->string('barcode_sku')->unique();
            $table->string('nama_obat');
            $table->string('nama_generik');
            $table->foreignId('categories_id')->constrained('categories')->onDelete('cascade');
            $table->string('satuan_kemasan');
            $table->integer('harga_jual_umum');
            $table->integer('harga_jual_medis');
            $table->integer('stok')->default(0);

            $table->integer('batas_stok_minimum')->default(10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obats');
    }
};
