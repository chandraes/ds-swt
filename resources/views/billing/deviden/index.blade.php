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
                        <!-- Tambahkan class investor-input pada input untuk diatur dengan Cleave.js -->
                        <input type="text" class="form-control investor-input" name="nilai[{{ $d->id }}]" id="nilai-{{ $d->id }}" readonly data-thousands=".">
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
<script src="{{asset('assets/js/cleave.min.js')}}"></script>
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script>
    $(function() {
        $('#nominal_transaksi').maskMoney({
            thousands: '.',
            decimal: ',',
            precision: 0,
            allowZero: true,
        });

        // Update nilai on keyup
        $('#nominal_transaksi').on('keyup', function(){
            let val = $(this).val().replace(/\./g,'');
            let dataInvestor = {!! json_encode($data) !!};

            dataInvestor.forEach(function(investor) {
                let persen = investor.persentase;
                let hasil = val * persen / 100;

                $(`#nilai-${investor.id}`).maskMoney({
                    thousands: '.',
                    decimal: ',',
                    precision: 0
                });

                $(`#nilai-${investor.id}`).maskMoney('mask', hasil);
            });
        });
    });

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
</script>

@endpush
