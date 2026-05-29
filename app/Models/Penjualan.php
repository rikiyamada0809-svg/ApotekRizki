<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $fillable = [
        'no_invoice',
        'tipe_pelanggan',
        'nama_instansi',
        'total_harga',
        'bayar',
        'kembali',
    ];

    public function details()
    {
        return $this->hasMany(DetailPenjualan::class, 'penjualan_id');
    }
}
