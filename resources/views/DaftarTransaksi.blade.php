@extends('LayoutNavbar')

@section('content')
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Daftar Transaksi</title>
    </head>
    <div class="container">
        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            </script>
        @endif
        <h3>Daftar Transaksi</h3>
        <div class="d-flex justify-content-between mb-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTransaksi">Tambah Transaksi</button>
            <a href="{{ route('transaksi.history') }}" class="btn btn-secondary">Lihat Riwayat</a>
        </div>
        <div class="modal fade" id="modalTambahTransaksi" tabindex="-1" aria-labelledby="modalTambahTransaksiLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="{{ route('transaksi.store') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahTransaksiLabel">Tambah Transaksi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div id="produk-list">
                                <div class="row mb-2 produk-item">
                                    <div class="col-md-6">
                                        <select name="barang_id[]" class="form-control" required>
                                            <option value="">Pilih Barang</option>
                                            @foreach($barangs as $barang)
                                            <option value="{{ $barang->id }}">{{ $barang->nama_barang }} - Rp {{ number_format($barang->harga, 0, ',', '.') }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" required min="1">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger remove">Hapus</button>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-secondary" id="tambah">Tambah Barang</button>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Simpan Transaksi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Total Barang</th>
                    <th>Total Harga</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $index => $trx)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $trx->created_at->format('d-m-Y H:i:s') }}</td>
                    <td>{{ $trx->total_barang }}</td>
                    <td>Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                    <td>
                        <form action="{{ route('transaksi.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Yakin hapus transaksi ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data transaksi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('tambah').addEventListener('click', function () {
            let produkList = document.getElementById('produk-list');
            let clone = produkList.querySelector('.produk-item').cloneNode(true);
            clone.querySelector('select').value = '';
            clone.querySelector('input').value = '';
            produkList.appendChild(clone);
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove')) {
                let produkList = document.getElementById('produk-list');
                if (produkList.querySelectorAll('.produk-item').length > 1) {
                    e.target.closest('.produk-item').remove();
                }
            }
        });
    </script>
@endsection
