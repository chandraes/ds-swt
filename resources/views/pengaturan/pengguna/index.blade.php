@extends('layouts.app')
@section('content')
<div class="container text-center">
    <h1>AKUN PENGGUNA</h1>
</div>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-header border-bottom-0 p-4">
                    <div class="card-options">
                        <a href="{{route('akun.store')}}" class="btn btn-primary">{{__('Add New')}} {{ucfirst(request()->segment(2))}}</a>
                    </div>
                </div>
                <div class="e-table px-5 pb-5">
                    <table id="basic-datatable" class="table dataTable table-bordered mb-0">
                        <thead>
                            <tr>
                                <td class="text-center">No</td>
                                <td class="text-center">Name</td>
                                <td class="text-center">E-Mail</td>
                                <td class="text-center">Username</td>
                                <td class="text-center">Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                            <tr>
                                <td class="text-center align-middle">{{$loop -> iteration}}</td>
                                <td class="text-center align-middle">{{$d->name}}</td>
                                <td class="text-center align-middle">{{$d->email}}</td>
                                <td class="text-center align-middle">{{$d->username}}</td>
                                <td class="text-center">
                                <div class="btn-group align-middle">
                                    <a class="btn btn-primary badge mx-2" data-target="#user-form-modal"
                                        data-bs-toggle="" type="button"
                                        href="{{route(request()->segment(2).'.edit', $d->id)}}">Edit</a>
                                    <form id="delete-form"
                                        action="{{ route(request()->segment(2).'.destroy', $d->id) }}"
                                        method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <button class="btn btn-danger badge" type="submit"><i
                                            class="fa fa-trash text-white"></i></button>
                                    </form>
                                </div>
                            </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection