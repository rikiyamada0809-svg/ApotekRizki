<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $fillable = [
        'barcode_sku',
        'nama_obat',
        'nama_generik',
        'categories_id',
        'satuan_kemasan',
        'harga_jual_umum',
        'harga_jual_medis',
        'stok',
        'batas_stok_minimum',
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class, 'categories_id');
    }
}
