<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index() {
        $barangs = Barang::all();   //Mengambil semua data dari tabel Barang
        return view('DaftarBarang', compact('barangs'));    //Menampilkan halaman view bernama DaftarBarang
    }

    public function history() {
        $barangs = Barang::onlyTrashed()->get();    //Menampilkan data barang yang sudah dihapus (soft delete) dari database
        return view('HistoryBarang', compact('barangs'));   //Mengirimkan data tersebut ke view HistoryBarang
    }

    public function destroy($id) {
        Barang::findOrFail($id)->delete();      //Mencari data barang berdasarkan $id, jika tidak ditemukan akan 404 dan secara soft delete
        return back()->with('success', 'Barang dihapus sementara');     //Kembali ke halaman sebelumnya dan mengirim pesan success
    }

    public function restore($id) {
        Barang::withTrashed()->findOrFail($id)->restore();      //Menampilkan data yang sudah di soft-delete berdasarkan id dan bisa memulihkan data ke normal
        return back()->with('success', 'Barang berhasil direstore');       //Kembali ke halaman sebelumnya dan mengirimkan pesan success
    }

    public function forceDelete($id) {
        Barang::withTrashed()->findOrFail($id)->forceDelete();  //Menampilkan data yang sudah di soft-delete berdasarkan id dan bisa menghapus secara permanen dari database
        return back()->with('success', 'Barang dihapus permanent');     //Kembali ke halaman sebelumnya dan mengirimkan pesan success
    }

    public function store(Request $request) {
        $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang',
            'nama_barang' => 'required',                                    //Melakukan pemeriksaan data apakah yang di input sudah sesuai atau belum
            'harga' => 'required|integer|min:0'
        ], [
            'kode_barang.unique' => 'Kode barang sudah di digunakan'       //Jika kode_barang sudah digunakan maka akan memunculkan pesan Kode sudah digunakan
        ]);

        Barang::create($request->all());    //Menyimpan data kedalam database
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');       //Jika berhasil, akan diarahkan kembali ke halaman daftar barang
    }
}
