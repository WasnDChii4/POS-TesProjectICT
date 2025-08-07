<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\DetailTransaksi;

class KasirController extends Controller
{
    public function index() {
        $barangs = Barang::all();       //Mengambil semua data Barang
        return view('Kasir', compact('barangs'));       //Menampilkan halaman view Kasir dan mengirim data ke dalamnya
    }

    public function store(Request $request) {
        $barangIds = $request->barang_id;       //barang_id dan jumlah adalah array input dari form
        $jumlahs = $request->jumlah;

        $totalBarang = 0;           //Untuk menghitung total semua barang yang dibeli dan total harga transaksi
        $totalHarga = 0;
        $detailTransaksis = [];     //Array yang digunakan untuk menyimpan data ke dalam tabel detail_transaksi

        foreach ($barangIds as $i => $id) {
            if (!$id || !isset($jumlahs[$i]) || $jumlahs[$i] < 1) {     //Loop setiap barang_id dan mencocokkan dengan jumlahnya di $jumlah[$i]
                continue;
            }

            $barang = Barang::find($id);
            if (!$barang) {
                continue;                       //Skip data jika barang_id kosong, jumlah tidak valid, dan barang tidak ditemukan di database
            }

            $jumlah = $jumlahs[$i];
            $totalBarang += $jumlah;
            $totalHarga += $barang->harga * $jumlah;        //Hitung total jumlah barang dan harga

            $detailTransaksis[] = [     //Simpan detailnya ke array $detailTransaksis
                'barang_id' => $id,
                'harga' => $barang->harga,
                'jumlah' => $jumlah,
            ];
        }

        if (empty($detailTransaksis)) {
            return redirect()->back()->with('error', 'Tidak ada data transaksi yang valid.');       //Jika semua input tidak valid, maka redirect balik dengan pesan error
        }

        $transaksi = Transaksi::create([        //Simpan data transaksi ke tabel transaksis
            'tanggal' => now(),
            'total_barang' => $totalBarang,
            'total_harga' => $totalHarga,
        ]);

        foreach ($detailTransaksis as $detail) {        //Untuk setiap barang yang valid, simpan ke tabel detail_transaksis dengan menghubungkannya ke transaksi induk (transaksi_id)
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'barang_id' => $detail['barang_id'],
                'harga' => $detail['harga'],
                'jumlah' => $detail['jumlah'],
            ]);
        }

        return redirect()->route('kasir.index')->with('success', 'Transaksi berhasil disimpan!');       //Kembali ke halaman utama kasir dengan notifikasi sukses
    }
}
