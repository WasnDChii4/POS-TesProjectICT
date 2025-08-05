<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangAPIController extends Controller
{
    public function index() {
        return response()->json(Barang::all(), 200);
    }

    public function show($kode) {
        $barang = Barang::where('kode_barang', $kode)->first();
        if (!$barang) {
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }
        return response()->json($barang, 200);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang',
            'nama_barang' => 'required',
            'harga' => 'required|integer|min:0',
        ]);

        $barang = Barang::create($validated);

        return response()->json([
            'message' => 'Barang berhasil ditambahkan',
            'data' => $barang
        ], 201);
    }
}
