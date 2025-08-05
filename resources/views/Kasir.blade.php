@extends('LayoutNavbar')

@section('content')
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Kasir</title>
    </head>
    <div class="container mt-4">
        <h2>Kasir</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('kasir.store') }}" method="POST">
            @csrf
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barangs as $barang)
                    <tr>
                        <td>
                            {{ $barang->nama_barang }}
                            <input type="hidden" name="barang_id[]" value="{{ $barang->id }}">
                        </td>
                        <td class="harga">{{ $barang->harga }}</td>
                        <td><input type="number" name="jumlah[]" class="form-control jumlah" min="0" value="0"></td>
                        <td class="total-per-barang">0</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <h4>Total Harga: <span id="totalHarga">0</span></h4>
            <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const hargaEls = document.querySelectorAll('.harga');
        const jumlahEls = document.querySelectorAll('.jumlah');
        const totalPerBarangEls = document.querySelectorAll('.total-per-barang');
        const totalHargaEl = document.getElementById('totalHarga');

        function toRupiah(angka) {
            return 'Rp ' + angka.toLocaleString('id-ID');
        }

        function hitungTotal() {
            let total = 0;
            jumlahEls.forEach((input, i) => {
                const jumlah = parseInt(input.value) || 0;
                const harga = parseInt(hargaEls[i].innerText) || 0;
                const subtotal = jumlah * harga;
                totalPerBarangEls[i].innerText = toRupiah(subtotal);
                total += subtotal;
            });
            totalHargaEl.innerText = toRupiah(total);
        }

        jumlahEls.forEach(input => {
            input.addEventListener('input', hitungTotal);
        });

        hitungTotal();
    </script>
@endpush

