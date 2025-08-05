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

    public function history() {
        $transaksis = Transaksi::onlyTrashed()->get();
        return view('HistoryTransaksi', compact('transaksis'));
    }

    public function destroy($id) {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus');
    }

    public function restore($id) {
        $transaksi = Transaksi::withTrashed()->findOrFail($id);
        $transaksi->restore();

        return redirect()->route('transaksi.history')->with('success', 'Transaksi berhasil dipulihkan');
    }

    public function forceDelete($id) {
        $transaksi = Transaksi::onlyTrashed()->where('id', $id)->firstOrFail();
        $transaksi->forceDelete();
        return redirect()->back()->with('success', 'Transaksi berhasil dihapus permanen.');
    }

    public function store(Request $request) {
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
            'tanggal' => date('Y-m-d H:i:s'),
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
