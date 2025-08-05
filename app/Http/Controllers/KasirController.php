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
        $barangIds = $request->barang_id;
        $jumlahs = $request->jumlah;

        $totalBarang = 0;
        $totalHarga = 0;

        $detailTransaksis = [];

        foreach ($barangIds as $i => $id) {
            if (!$id || !isset($jumlahs[$i]) || $jumlahs[$i] < 1) {
                continue;
            }

            $barang = Barang::find($id);
            if (!$barang) {
                continue;
            }

            $jumlah = $jumlahs[$i];
            $totalBarang += $jumlah;
            $totalHarga += $barang->harga * $jumlah;

            $detailTransaksis[] = [
                'barang_id' => $id,
                'harga' => $barang->harga,
                'jumlah' => $jumlah,
            ];
        }

        if (empty($detailTransaksis)) {
            return redirect()->back()->with('error', 'Tidak ada data transaksi yang valid.');
        }

        $transaksi = Transaksi::create([
            'tanggal' => now(),
            'total_barang' => $totalBarang,
            'total_harga' => $totalHarga,
        ]);

        foreach ($detailTransaksis as $detail) {
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'barang_id' => $detail['barang_id'],
                'harga' => $detail['harga'],
                'jumlah' => $detail['jumlah'],
            ]);
        }

        return redirect()->route('kasir.index')->with('success', 'Transaksi berhasil disimpan!');
    }
}
