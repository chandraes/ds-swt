@extends('layouts.app')
@section('content')
<div class="container text-center">
    <h1><u>BILLING</u></h1>
</div>
@include('swal')
<div class="container mt-5">

    <div class="row justify-content-left">
        <h4 class="mt-3">UMUM</h4>
         <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#formDeposit">
                <img src="{{asset('images/form-deposit.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM DEPOSIT</h4>
            </a>
            @include('billing.modal-form-deposit')
        </div>
         <div class="col-md-2 text-center mt-5">
            <a href="{{route('billing.deviden.index')}}" class="text-decoration-none">
                <img src="{{asset('images/form-deviden.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM DIVIDEN</h4>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM KASBON</h4>
            </a>
        </div>
        @if (auth()->user()->role == 'admin')
         <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalLain">
                <img src="{{asset('images/form-lain.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM LAIN-LAIN</h4>
            </a>
            <div class="modal fade" id="modalLain" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
                role="dialog" aria-labelledby="modalLainTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLainTitle">Form Lain-lain</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <select class="form-select" name="selectLain" id="selectLain">
                                <option value="masuk">Dana Masuk</option>
                                <option value="keluar">Dana Keluar</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-primary" onclick="funLain()">Lanjutkan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM GANTI RUGI</h4>
            </a>
        </div>




    </div>
    <hr>
    <br>
    <div class="row justify-content-left">
        <h4 class="mt-3">COST OPERATIONAL</h4>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM OPERATIONAL</h4>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM KAS KECIL</h4>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM GAJI</h4>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM BUNGA INVESTOR</h4>
            </a>
        </div>
    </div>
    <hr>
    <br>
    <div class="row justify-content-left">
        <h4 class="mt-3">KHUSUS</h4>
        <div class="col-md-2 text-center mt-5">
            <a href="{{route('home')}}" class="text-decoration-none" data-bs-toggle="modal"
                data-bs-target="#formSupplier">
                <img src="{{asset('images/form-supplier.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM SUPPLIER</h4>
            </a>
            @include('billing.modal-form-supplier')

        </div>
    </div>
    <hr>
    <br>
    <div class="row justify-content-left">
        <h4 class="mt-3">TRANSAKSI</h4>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalTransaksi">
                <img src="{{asset('images/transaksi.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM TRANSAKSI</h4>
            </a>
            @include('billing.modal-form-transaksi')

        </div>
    </div>
    <hr>
    <br>
    <div class="row justify-content-left">
        <h4 class="mt-3">NOTA</h4>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#notaTagihan">
                <img src="{{asset('images/nota-tagihan.svg')}}" alt="" width="70">
                <h4 class="mt-3">NOTA TAGIHAN @if($nt != 0) <span class="text-danger">({{$nt}})</span> @endif</h4>
            </a>
            @include('billing.modal-nota-tagihan')

        </div>
         <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalNotaBayar">
                <img src="{{asset('images/nota-bayar.svg')}}" alt="" width="70">
                <h4 class="mt-3">NOTA BAYAR  @if($nb != 0) <span class="text-danger">({{$nb}})</span> @endif</h4>
            </a>
            @include('billing.modal-nota-bayar')

        </div>
    </div>
    <hr>
    <br>
    <div class="row justify-content-left">
        <h4 class="mt-3">PAJAK</h4>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalInvoicePpn">
                <img src="{{asset('images/invoice-ppn.svg')}}" alt="" width="70">
                <h4 class="mt-3">INVOICE PPN  @if($ip != 0) <span class="text-danger">({{$ip}})</span> @endif</h4>
            </a>
            <div class="modal fade" id="modalInvoicePpn" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitleId">Invoice PPn</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                @foreach ($customer as $c)
                                <div class="col-md-3 m-2">
                                    <a href="{{route('invoice-ppn.index', ['customer' => $c->id])}}" class="text-decoration-none">
                                        <img src="{{asset('images/palm.svg')}}" alt="" width="70">
                                        <h3 class="mt-2">{{$c->singkatan}} @if($t->where('customer_id', $c->id)->where('tagihan', 1)->where('ppn', 0)->count() != 0) <span class="text-danger">({{$t->where('customer_id', $c->id)->where('tagihan', 1)->where('ppn', 0)->count()}})</span> @endif</h3>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <div class="col-md-2 text-center mt-5">
            <a href="{{route('form-ppn')}}" class="text-decoration-none">
                <img src="{{asset('images/form-ppn.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM PPN @if($ppn != 0) <span class="text-danger">({{$ppn}})</span> @endif</h4>
            </a>
        </div>
         <div class="col-md-2 text-center mt-5">
            <a href="{{route('home')}}" class="text-decoration-none">
                <img src="{{asset('images/dashboard.svg')}}" alt="" width="70">
                <h4 class="mt-3">DASHBOARD</h4>
            </a>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    function funDeposit(){
        var selectDeposit = document.getElementById('selectDeposit').value;
        if(selectDeposit == 'masuk'){
            window.location.href = "{{route('form-deposit.masuk')}}";
        }else if(selectDeposit == 'keluar'){
            window.location.href = "{{route('form-deposit.keluar')}}";
        }
    }

    function funLain(){
        var selectLain = document.getElementById('selectLain').value;
        if(selectLain == 'masuk'){
            window.location.href = "{{route('form-lain.masuk')}}";
        }else if(selectLain == 'keluar'){
            window.location.href = "{{route('form-lain.keluar')}}";
        }
    }

    function funSupplier(){
        var selectFormSupplier = document.getElementById('selectFormSupplier').value;
        if(selectFormSupplier == 'masuk'){
            window.location.href = "{{route('form-supplier.titipan')}}";
        } else if(selectFormSupplier == 'keluar'){
            window.location.href = "{{route('form-supplier.pengembalian')}}";
        }
    }
</script>
@endpush
