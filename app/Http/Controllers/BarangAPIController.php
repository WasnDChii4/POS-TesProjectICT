<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangAPIController extends Controller
{
    public function index() {
        return response()->json(Barang::all());
    }

    public function show($kode) {
        $barang = Barang::where('kode_barang', $kode)->first();
        if (!$barang) {
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }
        return response()->json($barang);
    }
}
