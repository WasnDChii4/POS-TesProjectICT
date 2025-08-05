@extends('LayoutNavbar')

@section('content')
    <head>
        <meta charset="UTF-8">
        <title>Riwayat Barang Dihapus</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <div class="container">
        <h3>Riwayat Transaksi</h3>
        <a href="{{ route('transaksi.index') }}" class="btn btn-primary mb-4">Kembali ke Daftar Transaksi</a>
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
                    <td>{{ $trx->tanggal }}</td>
                    <td>{{ $trx->total_barang }}</td>
                    <td>Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('transaksi.restore', $trx->id) }}" class="btn btn-sm btn-success">Restore</a>

                        <form action="{{ route('transaksi.forceDelete', $trx->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus permanen?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus Permanen</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">Tidak ada riwayat transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
