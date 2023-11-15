@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center mb-5">
        <div class="col-md-12 text-center">
            <h1><u>Form Deviden</u></h1>
        </div>
    </div>
    @include('swal')
    <form action="{{ route('billing.deviden.store') }}" method="post" id="masukForm">
        @csrf
        <div class="row">
            <div class="col-4 mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="text" class="form-control @if ($errors->has('tanggal')) is-invalid @endif" name="tanggal" id="tanggal" value="{{ date('d-m-Y') }}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label for="nominal_transaksi" class="form-label">Nominal</label>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Rp</span>
                    <input type="text" class="form-control @if ($errors->has('nominal_transaksi')) is-invalid @endif" name="nominal_transaksi" id="nominal_transaksi" required data-thousands=".">
                </div>
                @if ($errors->has('nominal_transaksi'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nominal_transaksi') }}
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            @foreach ($data as $d)
                <div class="col-md-6 mb-3">
                    <label for="investor" class="form-label">{{ $d->nama }} ({{ $d->persentase }}%)</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">Rp</span>
                        <input type="text" class="form-control" name="nilai[{{ $d->id }}]" id="nilai-{{ $d->id }}" readonly data-thousands=".">
                    </div>
                </div>
            @endforeach
        </div>
        <hr>
        <div class="d-grid gap-3 mt-3">
            <button class="btn btn-success" type="submit">Simpan</button>
            <a href="{{ route('billing') }}" class="btn btn-secondary" type="button">Batal</a>
        </div>
    </form>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/js/jquery.maskMoney.js') }}"></script>
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var nominalInput = document.getElementById('nominal_transaksi');
        var masukForm = document.getElementById('masukForm');

        nominalInput.addEventListener('input', function () {
            var inputValue = nominalInput.value;

            // Menghapus semua karakter selain angka dan koma
            var numericValue = inputValue.replace(/[^\d,]/g, '');

            // Mengganti koma dengan titik jika diperlukan
            numericValue = numericValue.replace(',', '.');

            // Memastikan bahwa nilai adalah angka atau string kosong
            numericValue = isNaN(parseFloat(numericValue)) ? '' : numericValue;

            // Menambahkan pemisah ribuan pada input
            nominalInput.value = formatRupiah(numericValue);

            // Mengupdate nilai pada setiap investor
            updateInvestorValues(numericValue);
        });

        masukForm.addEventListener('submit', function () {
            var nominalValue = nominalInput.value;

            // Menghapus semua karakter selain angka
            var numericNominalValue = nominalValue.replace(/[^\d]/g, '');

            // Memastikan bahwa nilai adalah angka atau string kosong
            numericNominalValue = isNaN(parseFloat(numericNominalValue)) ? '' : numericNominalValue;

            // Mengganti nilai input dengan nilai yang sudah diformat
            nominalInput.value = formatRupiah(numericNominalValue);
        });
    });

    function formatRupiah(angka, prefix) {
        var number_string = angka.toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('');
        }

        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix === undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    function updateInvestorValues(value) {
        var dataInvestor = {!! json_encode($data) !!};

        // each investor
        dataInvestor.forEach(function (investor) {
            var persen = investor.persentase;
            var hasil = value * persen / 100;

            var investorInput = document.getElementById('nilai-' + investor.id);

            // Memastikan bahwa nilai adalah angka atau string kosong
            var numericInvestorValue = isNaN(parseFloat(hasil)) ? '' : hasil;

            // Menghapus pemisah ribuan pada input investor
            investorInput.value = numericInvestorValue;
        });
    }
</script>



<script>
    $(function() {
        

        // masukForm on submit, sweetalert confirm
        $('#masukForm').submit(function(e){
            e.preventDefault();
            Swal.fire({
                title: 'Apakah data sudah benar?',
                text: "Pastikan data sudah benar sebelum disimpan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, simpan!'
                }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        });
    });
</script>
@endpush
