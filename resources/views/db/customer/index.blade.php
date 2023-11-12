@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 text-center">
            <h1><u>CUSTOMER</u></h1>
        </div>
    </div>
    <div class="flex-row justify-content-between mt-3">
        <div class="col-md-6">
            <table class="table" id="data-table">
                <tr>
                    <td><a href="{{route('home')}}"><img src="{{asset('images/dashboard.svg')}}" alt="dashboard"
                                width="30"> Dashboard</a></td>
                    <td><a href="{{route('db')}}"><img src="{{asset('images/database.svg')}}" alt="dokumen"
                                width="30"> Database</a></td>
                    <td><a href="#"  data-bs-toggle="modal" data-bs-target="#createCustomer"><img src="{{asset('images/customer.svg')}}"
                                width="30"> Tambah Customer</a>
                        @include('db.customer.create')
                    </td>

                </tr>
            </table>
        </div>
    </div>
</div>

<div class="container mt-5 table-responsive">
    <table class="table table-bordered table-hover" id="data">
        <thead class="table-warning bg-gradient">
            <tr>
                <th class="text-center align-middle" style="width: 5%">No</th>
                <th class="text-center align-middle">Nama</th>
                <th class="text-center align-middle">Contact Person</th>
                <th class="text-center align-middle">No Wa</th>
                <th class="text-center align-middle">Harga</th>
                <th class="text-center align-middle">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $d)
                <tr>
                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                    <td class="text-center align-middle">{{$d->nama}}</td>
                    <td class="text-center align-middle">{{$d->cp}}</td>
                    <td class="text-center align-middle">{{$d->no_wa}}</td>
                    <td class="text-center align-middle">
                        <a href="#" class="btn btn-primary">
                            Rp. {{$d->harga}}
                        </a>
                    </td>
                    <td class="text-center align-middle">
                        <form action="{{route('db.customer.delete', $d)}}" method="post" id="deleteForm-{{$d->id}}">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger"><i
                                    class="fa fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <script>
                     $('#deleteForm-{{$d->id}}').submit(function(e){
                            e.preventDefault();
                            Swal.fire({
                                title: 'Apakah data yakin untuk menghapus data ini?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#6c757d',
                                confirmButtonText: 'Ya, hapus!'
                                }).then((result) => {
                                if (result.isConfirmed) {
                                    this.submit();
                                }
                            })
                        });
                </script>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@push('css')
<link href="{{asset('assets/css/dt.min.css')}}" rel="stylesheet">
@endpush
@push('js')

<script src="{{asset('assets/js/cleave.min.js')}}"></script>

<script src="{{asset('assets/js/dt5.min.js')}}"></script>
<script>
    $('#data').DataTable({
        paging: false,
        scrollCollapse: true,
        scrollY: "550px",
    });

    var cleave = new Cleave('#harga', {
        numeral: true,
        numeralThousandsGroupStyle: 'thousand',
        numeralDecimalMark: ',',
        delimiter: '.'
    });

    var wa = new Cleave('#no_wa', {
        delimiter: '-',
        blocks: [4, 4, 8]
    });

    $('#createForm').submit(function(e){
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

