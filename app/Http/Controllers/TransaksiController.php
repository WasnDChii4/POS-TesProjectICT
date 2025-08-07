<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\DetailTransaksi;

class TransaksiController extends Controller
{
    public function index() {
        $transaksis = Transaksi::with('detailTransaksis.barang')->get();        //Mengambil semua data dari tabel transaksis dengan relasi ke tabel detail_transaksis
        $barangs = Barang::all();       //Mengambil sema data dari tabel Barang
        return view('DaftarTransaksi', compact('transaksis', 'barangs'));       //Menampilkan view halaman DaftarTransaksi dan mengirimkan dua data $transaksis dan $barangs
    }

    public function history() {
        $transaksis = Transaksi::onlyTrashed()->get();      //Menampilkan data transaksi yang sudah dihapus (soft delete) dari database
        return view('HistoryTransaksi', compact('transaksis'));     //Mengirimkan data tersebut ke view HistoryTransaksi
    }

    public function destroy($id) {
        Transaksi::findOrFail($id)->delete();    //Mencari data transaksi berdasarkan $id, jika tidak ditemukan akan 404 dan secara soft delete
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus');     //Kembali ke halaman sebelumnya dan mengirim pesan success
    }

    public function restore($id) {
        $transaksi = Transaksi::withTrashed()->findOrFail($id)->restore();      //Menampilkan data yang sudah di soft-delete berdasarkan id dan bisa memulihkan data ke normal
        return redirect()->route('transaksi.history')->with('success', 'Transaksi berhasil dipulihkan');    //Kembali ke halaman sebelumnya dan mengirimkan pesan success
    }

    public function forceDelete($id) {
        $transaksi = Transaksi::onlyTrashed()->where('id', $id)->firstOrFail()->forceDelete();      //Menampilkan data yang sudah di soft-delete berdasarkan id dan bisa menghapus secara permanen dari database
        return redirect()->back()->with('success', 'Transaksi berhasil dihapus permanen.');     //Kembali ke halaman sebelumnya dan mengirimkan pesan success
    }

    public function store(Request $request) {
        $request->validate([        //Memastikan array barang_id dan jumlah harus ada di dalam tabel
            'barang_id.*' => 'required|exists:barangs,id',
            'jumlah.*' => 'required|integer|min:1',
        ]);

        $totalBarang = 0;   //VAriable penampung untuk menghitung
        $totalHarga = 0;

        foreach ($request->barang_id as $index => $id) {        //Melakukan loop berdasarkan barang_id
            $barang = Barang::find($id);        //Mengambil harga dari database Barang
            $jumlah = $request->jumlah[$index];     //Ambil jumlah dari array input
            $totalBarang += $jumlah;
            $totalHarga += ($barang->harga * $jumlah);      //Hitung total barang dan total harga
        }

        $transaksi = Transaksi::create([        //Menyimpan data transaksi utama ke dalam database
            'tanggal' => date('Y-m-d H:i:s'),
            'total_barang' => $totalBarang,
            'total_harga' => $totalHarga,
        ]);

        foreach ($request->barang_id as $index => $id) {    //Melakukan loop lagi untuk menyimpan detail dari masing-masing barang ke tabel detail_transakis
            $barang = Barang::find($id);
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'barang_id' => $id,
                'harga' => $barang->harga,
                'jumlah' => $request->jumlah[$index],
            ]);
        }

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan');        //Kembali ke halaman daftar transaksi dan menampilkan pesan success
    }
}
