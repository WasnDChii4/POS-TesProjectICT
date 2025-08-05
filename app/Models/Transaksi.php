<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tanggal',
        'total_barang',
        'total_harga'
    ];

    public function detailTransaksis() {
        return $this->hasMany(DetailTransaksi::class);
    }
}
