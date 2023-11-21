@extends('layouts.app')
@section('content')
@php
$supplier = App\Models\Supplier::select('id', 'nama', 'nickname')->get();
@endphp
<div class="container text-center">
    <h1>REKAP</h1>
</div>
<div class="container mt-5">
    <div class="row justify-content-left">
        <div class="col-md-3 mt-3 text-center">
            <a href="{{route('rekap.kas-besar')}}" class="text-decoration-none">
                <img src="{{asset('images/kas-besar.svg')}}" alt="" width="100">
                <h2>KAS BESAR</h2>
            </a>
        </div>
        <div class="col-md-3 mt-3 text-center">
            <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#kasSupplier">
                <img src="{{asset('images/kas-supplier.svg')}}" alt="" width="100">
                <h2>KAS SUPPLIER</h2>
            </a>
            <div class="modal fade" id="kasSupplier" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
                role="dialog" aria-labelledby="kasSupplierTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="kasSupplierTitle">Kas Supplier</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{route('rekap.kas-supplier')}}" method="get">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <select class="form-select" name="supplier" id="" required>
                                        <option value="">-- Pilih Supplier --</option>
                                        @foreach ($supplier as $i)
                                        <option value="{{$i->id}}">{{$i->nama}} ({{$i->nickname}})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Lanjutkan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mt-3 text-center">
            <a href="{{route('home')}}" class="text-decoration-none">
                <img src="{{asset('images/dashboard.svg')}}" alt="" width="100">
                <h2>DASHBOARD</h2>
            </a>
        </div>

    </div>
</div>
@endsection
