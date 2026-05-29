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
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice')->unique();
            $table->enum('tipe_pelanggan', ['umum', 'medis'])->default('umum');
            $table->string('nama_instansi')->nullable();
            $table->integer('total_harga');
            $table->integer('bayar');
            $table->integer('kembali');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
