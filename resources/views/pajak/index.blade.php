@extends('layouts.app')
@section('content')
<div class="container text-center">
    <h1><u>PAJAK</u></h1>
</div>
<div class="container mt-3">
    <div class="row justify-content-left">
        <div class="col-md-3 text-center mt-3">
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
         <div class="col-md-3 text-center mt-3">
            <a href="{{route('form-ppn')}}" class="text-decoration-none">
                <img src="{{asset('images/form-ppn.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM PPN @if($ppn != 0) <span class="text-danger">({{$ppn}})</span> @endif</h4>
            </a>
        </div>
        <div class="col-md-3 text-center mt-3">
            <a href="{{route('home')}}" class="text-decoration-none">
                <img src="{{asset('images/dashboard.svg')}}" alt="" width="70">
                <h4 class="mt-3">DASHBOARD</h4>
            </a>
        </div>
    </div>
    </div>
</div>
@endsection
