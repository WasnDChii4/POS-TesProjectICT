<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index() {
        $barangs = Barang::all();
        return view('DaftarBarang', compact('barangs'));
    }

    public function history() {
        $barangs = Barang::onlyTrashed()->get();
        return view('HistoryBarang', compact('barangs'));
    }

    public function store(Request $request) {
        $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang',
            'nama_barang' => 'required',
            'harga' => 'required|integer|min:0'
        ], [
            'kode_barang.unique' => 'Kode barang sudah di digunakan'
        ]);

        Barang::create($request->all());
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function destroy($id) {
        Barang::findOrFail($id)->delete();
        return back()->with('success', 'Barang dihapus sementara');
    }

    public function restore($id) {
        Barang::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Barang berhasil direstore');
    }

    public function forceDelete($id) {
        Barang::withTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'Barang dihapus permanent');
    }

    public function apiStore(Request $request) {
        $request->validate([
            'kode_barang' => 'required|unique:barangs',
            'nama_barang' => 'required',
            'harga' => 'required|integer|min:0'
        ]);

        $barang = Barang::create($request->all());
        return response()->json(['message' => 'Barang berhasil ditambahkan', 'data' => $barang], 201);
    }

    public function apiShow($kode) {
        $barang = Barang::where('kode_barang', $kode)->first();

        if (!$barang) {
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }

        return response()->json($barang);
    }
}
