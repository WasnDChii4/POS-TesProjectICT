<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangAPIController extends Controller
{
    public function index() {
        $barang = Barang::all();

        return response()->json([
            'status' => 'success',
            'data' => $barang
        ]);
    }

    public function show($kode) {
        $barang = Barang::where('kode_barang', $kode)->first();

        if (!$barang) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $barang
        ]);
    }

}
