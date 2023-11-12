@extends('layouts.app')
@section('content')
<div class="container text-center">
    <h1>BILLING</h1>
</div>
<div class="container mt-5">
    <div class="row justify-content-left">
        <div class="col-md-3 text-center mt-3">
            <a href="{{route('home')}}" class="text-decoration-none">
                <img src="{{asset('images/form-deposit.svg')}}" alt="" width="100">
                <h2>FORM DEPOSIT</h2>
            </a>
        </div>
        <div class="col-md-3 text-center mt-3">
            <a href="{{route('home')}}" class="text-decoration-none">
                <img src="{{asset('images/dashboard.svg')}}" alt="" width="100">
                <h2>DASHBOARD</h2>
            </a>
        </div>
    </div>
</div>
@endsection

