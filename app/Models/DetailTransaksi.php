<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailTransaksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaksi_id',
        'barang_id',
        'harga',
        'jumlah'
    ];

    public function barang() {
        return $this->belongsTo(Barang::class);
    }
}
