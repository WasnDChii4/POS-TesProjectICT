@extends('LayoutNavbar')

@section('content')
    <head>
        <meta charset="UTF-8">
        <title>Riwayat Barang Dihapus</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        <h2>Riwayat Barang Dihapus</h2>
        <div class="mb-3">
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali ke Daftar Barang</a>
        </div>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($barangs as $barang)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $barang->kode_barang }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>Rp{{ number_format($barang->harga, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('barang.restore', $barang->id) }}" class="btn btn-warning btn-sm">Restore</a>
                            <form action="{{ route('barang.forceDelete', $barang->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus permanen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Force Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data terhapus</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
