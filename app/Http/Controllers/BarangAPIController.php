<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangAPIController extends Controller
{
    public function index() {
        return response()->json(Barang::all(), 200);    //Mengambil semua database barang dan mengembalikan dalam bentuk JSON dengan status HTTP 200
    }

    public function show($kode) {
        $barang = Barang::where('kode_barang', $kode)->first();     //Mengambil data barang pertama dari tabel barang yang memiliki nilai kode_barang sesuai $kode
        if (!$barang) {     //Mengecek apakah data ada yang cocok atau tidak ditemukan
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);      //Mengembalikan JSON dengan pesan "Barang tidak ditemukan"
        }
        return response()->json($barang, 200);      //Jika ditemukan, akan dikembalikan sebagai JSON dengan data barang dan status 200
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang',
            'nama_barang' => 'required',                                    //Melakukan pemeriksaan data apakah yang di input sudah sesuai atau belum
            'harga' => 'required|integer|min:0',
        ]);

        $barang = Barang::create($validated);       //Menggunakan mass assignment untuk menyimpan data yang sudah tervalidasi ke dalam database

        return response()->json([
            'message' => 'Barang berhasil ditambahkan',     //Memberikan response JSON dengan pesan sukses dan data barang dibuat
            'data' => $barang
        ], 201);
    }
}
