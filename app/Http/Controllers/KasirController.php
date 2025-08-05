<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\DetailTransaksi;

class KasirController extends Controller
{
    public function index() {
        $barangs = Barang::all();
        return view('Kasir', compact('barangs'));
    }

    public function store(Request $request) {
        $request->validate([
            'barang_id.*' => 'required|exists:barangs,id',
            'jumlah.*' => 'required|integer|min:1',
        ]);

        $totalBarang = 0;
        $totalHarga = 0;

        foreach ($request->barang_id as $i => $id) {
            $jumlah = $request->jumlah[$i];
            $barang = Barang::find($id);
            $totalBarang += $jumlah;
            $totalHarga += $barang->harga * $jumlah;
        }

        $transaksi = Transaksi::create([
            'total_barang' => $totalBarang,
            'total_harga' => $totalHarga,
        ]);

        foreach ($request->barang_id as $i => $id) {
            $jumlah = $request->jumlah[$i];
            $barang = Barang::find($id);

            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'barang_id' => $id,
                'harga' => $barang->harga,
                'jumlah' => $jumlah,
            ]);
        }

        return redirect()->route('kasir.index')->with('success', 'Transaksi berhasil disimpan!');
    }
}
