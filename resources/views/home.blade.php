@extends('layouts.app')
@section('content')
<div class="container text-center">
    <h1><u>DASHBOARD</u></h1>
</div>
<div class="container mt-5">
    <div class="row justify-content-left">
        @if (auth()->user()->role != 'supplier' && auth()->user()->role != 'investor')
        <div class="col-md-3 text-center mb-5 mt-3">
            <a href="{{route('db')}}" class="text-decoration-none">
                <img src="{{asset('images/database.svg')}}" alt="" width="70">
                <h4 class="mt-2">DATABASE</h4>
            </a>
        </div>
        @endif
        @if (auth()->user()->role != 'supplier' && auth()->user()->role != 'investor')
        <div class="col-md-3 text-center mb-5 mt-3">
            <a href="{{route('billing')}}" class="text-decoration-none">
                <img src="{{asset('images/billing.svg')}}" alt="" width="70">
                <h4 class="mt-2">BILLING</h4>
            </a>
        </div>
        @endif
        @if (auth()->user()->role != 'supplier')
        <div class="col-md-3 text-center mb-5 mt-3">
            <a href="{{route('rekap')}}" class="text-decoration-none">
                <img src="{{asset('images/rekap.svg')}}" alt="" width="70">
                <h4 class="mt-2">REKAP</h4>
            </a>
        </div>
        @endif
        <div class="col-md-3 text-center mb-5 mt-3">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h4 class="mt-2">PURCHASE<br>ORDER</h4>
            </a>
        </div>
        <div class="col-md-3 text-center mb-5 mt-3">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h4 class="mt-2">INVENTARIS</h4>
            </a>
        </div>
        <div class="col-md-3 text-center mb-5 mt-3">
            <a href="{{route('pajak.index')}}" class="text-decoration-none">
                <img src="{{asset('images/pajak-menu.svg')}}" alt="" width="70">
                <h4 class="mt-2">PAJAK</h4>
            </a>
        </div>
        <div class="col-md-3 text-center mb-5 mt-3">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h4 class="mt-2">LAPORAN<br>KEUANGAN</h4>
            </a>
        </div>
        <div class="col-md-3 text-center mb-5 mt-3">
            <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalStatistik">
                <img src="{{asset('images/statistik.svg')}}" alt="" width="70">
                <h4 class="mt-3">STATISTIK</h4>
            </a>
            <div class="modal fade" id="modalStatistik" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalStatistikTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalStatistikTitle">Statistik</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                @foreach ($customer as $c)
                                <div class="col-md-3 m-2">
                                    <a href="{{route('statistik.index', ['customer' => $c->id])}}" class="text-decoration-none">
                                        <img src="{{asset('images/palm.svg')}}" alt="" width="70">
                                        <h3 class="mt-2">{{$c->singkatan}}</h3>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 text-center mb-5 mt-3">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h4 class="mt-2">LEGALITAS</h4>
            </a>
        </div>
        <div class="col-md-3 text-center mb-5 mt-3">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h4 class="mt-2">STRUKTUR<br>ORGANISASI</h4>
            </a>
        </div>
        <div class="col-md-3 text-center mb-5 mt-3">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h4 class="mt-2">DOKUMEN</h4>
            </a>
        </div>
        <div class="col-md-3 text-center mb-5 mt-3">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h4 class="mt-2">COMPANY PROFILE</h4>
            </a>
        </div>
        @if (auth()->user()->role == 'admin')

        <div class="col-md-3 text-center mb-5 mt-3">
            <a href="{{route('pengaturan')}}" class="text-decoration-none">
                <img src="{{asset('images/pengaturan.svg')}}" alt="" width="70">
                <h4 class="mt-2">PENGATURAN</h4>
            </a>
        </div>
        @endif
        @if (auth()->user()->role == 'supplier')
        <div class="col-md-3 text-center mb-5 mt-3">
            <a href="{{route('kas-per-supplier')}}" class="text-decoration-none">
                <img src="{{asset('images/kas-supplier.svg')}}" alt="" width="70">
                <h4 class="mt-2">KAS SUPPLIER</h4>
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

