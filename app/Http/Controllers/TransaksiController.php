<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\DetailTransaksi;

class TransaksiController extends Controller
{
    public function index() {
        $transaksis = Transaksi::latest()->get();
        $barangs = Barang::all();
        return view('DaftarTransaksi', compact('transaksis', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id.*' => 'required|exists:barangs,id',
            'jumlah.*' => 'required|integer|min:1',
        ]);

        $totalBarang = 0;
        $totalHarga = 0;

        foreach ($request->barang_id as $index => $id) {
            $barang = Barang::find($id);
            $jumlah = $request->jumlah[$index];
            $totalBarang += $jumlah;
            $totalHarga += ($barang->harga * $jumlah);
        }

        $transaksi = Transaksi::create([
            'tanggal' => date('Y-m-d H:i:s'), // native PHP
            'total_barang' => $totalBarang,
            'total_harga' => $totalHarga,
        ]);

        foreach ($request->barang_id as $index => $id) {
            $barang = Barang::find($id);
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'barang_id' => $id,
                'harga' => $barang->harga,
                'jumlah' => $request->jumlah[$index],
            ]);
        }

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan');
    }
}
