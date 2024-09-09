@extends('layouts.app')
@section('content')
<div class="container text-center">
    <h1><u>PENGATURAN</u></h1>
</div>
<div class="container mt-5">
    <div class="row justify-content-left">
        <h2 class="mt-2">PENGGUNA</h2>
        <div class="col-md-3 text-center mt-5">
            <a href="{{route('pengaturan.akun')}}" class="text-decoration-none">
                <img src="{{asset('images/pengguna.svg')}}" alt="" width="70">
                <h5 class="mt-3">AKUN</h5>
            </a>
        </div>
        <div class="col-md-3 text-center mt-5">
            <a href="{{route('pengaturan.wa')}}" class="text-decoration-none">
                <img src="{{asset('images/wa.svg')}}" alt="" width="70">
                <h5 class="mt-3">GROUP WHATSAPP</h5>
            </a>
        </div>
        <div class="col-md-3 text-center mt-5">
            {{-- <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#passwordKonfirmasi"> --}}
                <a href="#" class="text-decoration-none">
                    <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h5 class="mt-3">PASSWORD KONFIRMASI</h5>
            </a>
            {{-- <div class="modal fade" id="passwordKonfirmasi" tabindex="-1" data-bs-backdrop="static"
                data-bs-keyboard="false" role="dialog" aria-labelledby="pkTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="pkTitle">Password Konfirmasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{route('pengaturan.password-konfirmasi')}}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="Password" aria-label="Password" aria-describedby="password"
                                            value="{{$password ? $password->password : ''}}" required>
                                        <button class="btn btn-outline-secondary" type="button" id="button-addon2"
                                            onclick="togglePassword()">
                                            <i class="fa fa-eye" id="icon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
    <hr>
    <br>
    <div class="row justify-content-left">
        <h2 class="mt-2">OTHERS</h2>
        <div class="col-md-3 text-center mt-5">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h5 class="mt-3">APLIKASI</h5>
            </a>
        </div>
        <div class="col-md-3 text-center mt-5">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h5 class="mt-3">BATASAN</h5>
            </a>
        </div>
       <div class="col-md-3 text-center mt-5">
            <a href="{{route('histori-pesan')}}" class="text-decoration-none">
                <img src="{{asset('images/histori.svg')}}" alt="" width="70">
                <h5 class="mt-3">HISTORI PESAN WHATSAPP</h5>
            </a>
        </div>
        <div class="col-md-3 text-center mt-5">
            <a href="{{route('home')}}" class="text-decoration-none">
                <img src="{{asset('images/dashboard.svg')}}" alt="" width="70">
                <h5 class="mt-3">DASHBOARD</h5>
            </a>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    function togglePassword() {
            var x = document.getElementById("password");
            var y = document.getElementById("icon");
            if (x.type === "password") {
                x.type = "text";
                y.className = "fa fa-eye-slash";
            } else {
                x.type = "password";
                y.className = "fa fa-eye";
            }
        }
</script>
@endpush
