@extends('LayoutNavbar')

@section('content')
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Kasir</title>
    </head>
    <div class="container">
        <h3>Kasir</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('kasir.store') }}">
            @csrf

            <div id="barang-wrapper">
                <div class="row mb-2 barang-item">
                    <div class="col-md-5">
                        <select name="barang_id[]" class="form-control" required>
                            <option value="">Pilih Barang</option>
                            @foreach($barangs as $barang)
                                <option value="{{ $barang->id }}">{{ $barang->nama_barang }} - Rp{{ number_format($barang->harga) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" min="1" required>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-secondary mb-2" onclick="tambahBarang()">Tambah Barang</button><br>
            <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
        </form>
    </div>

    <script>
    function tambahBarang() {
        const wrapper = document.getElementById('barang-wrapper');
        const item = wrapper.querySelector('.barang-item');
        const clone = item.cloneNode(true);
        wrapper.appendChild(clone);
    }
    </script>
@endsection
