<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function index() {
        $barangs = Barang::all();
        return view('Kasir', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id.*' => 'required|exists:barangs,id',
            'jumlah.*' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $totalBarang = 0;
            $totalHarga = 0;

            $transaksi = Transaksi::create([
                'tanggal' => now(),
                'total_barang' => 0,
                'total_harga' => 0,
            ]);

            foreach ($request->barang_id as $index => $barangId) {
                $barang = Barang::findOrFail($barangId);
                $jumlah = $request->jumlah[$index];
                $subtotal = $barang->harga * $jumlah;

                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id,
                    'id_barang' => $barangId,
                    'harga' => $barang->harga,
                    'jumlah' => $jumlah,
                ]);

                $totalBarang += $jumlah;
                $totalHarga += $subtotal;
            }

            $transaksi->update([
                'total_barang' => $totalBarang,
                'total_harga' => $totalHarga,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Transaksi berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
