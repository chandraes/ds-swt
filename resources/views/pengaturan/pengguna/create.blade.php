@extends('layouts.app')
@section('content')
<div class="container text-center">
    <h1>CREATE AKUN PENGGUNA</h1>
</div>
<div class="container mt-5">
    <div class="row row-cards">
        <div class="col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 p-9">
                            <form class="form-horizontal" method="POST" action="{{route('akun.store')}}">
                                @csrf
                                <div class=" row mb-4">
                                    <label class="col-md-3 form-label">Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control @error('name')
                                            is-invalid
                                        @enderror" name="name" placeholder="Full Name">
                                        @error('name')
                                        <span class="invalid-feedback text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class=" row mb-4">
                                    <label class="col-md-3 form-label">Username</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control @error('username')
                                            is-invalid
                                        @enderror" name="username" placeholder="Username">
                                        @error('username')
                                        <span class="invalid-feedback text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class=" row mb-4">
                                    <label class="col-md-3 form-label" for="example-email">Email</label>
                                    <div class="col-md-9">
                                        <input type="email" id="example-email" name="email" class="form-control @error('email')
                                        is-invalid
                                    @enderror" placeholder="Email">
                                        @error('email')
                                        <span class="invalid-feedback text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class=" row mb-4">
                                    <label class="col-md-3 form-label">Password</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control @error('password')
                                        is-invalid
                                    @enderror" name="password"
                                            placeholder="*************">
                                        @error('password')
                                        <span class="invalid-feedback text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class=" row mb-4">
                                    <label class="col-md-3 form-label">Password Confirmation</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control @error('password_confirmation')
                                        is-invalid
                                    @enderror" name="password_confirmation"
                                            placeholder="*************">
                                            @error('password_confirmation')
                                        <span class="invalid-feedback text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-footer mt-2 row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-9">
                                        <button type="submit" class="btn btn-success">Submit</button>
                                        <a href="{{route(request()->segment(2).'.index')}}"
                                            class="btn btn-primary mx-2">Cancel</a>
                                    </div>

                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- COL-END -->
    </div>
</div>
@endsection
