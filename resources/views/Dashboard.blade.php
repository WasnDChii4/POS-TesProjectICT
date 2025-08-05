@extends('LayoutNavbar')

@section('content')
    <div class="container mt-5 text-center">
        <h1 class="mb-3">Selamat Datang di Aplikasi Point of Sales</h1>
        <p class="lead">Kelola transaksi penjualan, data barang, dan riwayat transaksi Anda dengan mudah dan cepat.</p>

        <div class="mt-4">
            <a href="{{ route('kasir.index') }}" class="btn btn-primary me-2">Mulai Transaksi</a>
            <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary">Lihat Daftar Barang</a>
        </div>
    </div>
@endsection
