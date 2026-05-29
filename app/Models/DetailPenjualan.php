<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    protected $table = 'detail_penjualans';

    protected $fillable = [
        'penjualan_id',
        'obat_id',
        'qty',
        'harga_satuan',
        'subtotal',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }
}
