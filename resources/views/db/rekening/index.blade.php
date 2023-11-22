@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 text-center">
            <h1><u>REKENING TRANSAKSI</u></h1>
        </div>
    </div>
    <div class="flex-row justify-content-between mt-3">
        <div class="col-md-6">
            <table class="table" id="data-table">
                <tr>
                    <td><a href="{{route('home')}}"><img src="{{asset('images/dashboard.svg')}}" alt="dashboard"
                                width="30"> Dashboard</a></td>
                    <td><a href="{{route('db')}}"><img src="{{asset('images/database.svg')}}" alt="dokumen" width="30">
                            Database</a></td>
                </tr>
            </table>
        </div>
    </div>
</div>
@include('db.rekening.edit')
<div class="container mt-5 table-responsive">
    <table class="table table-bordered table-hover" id="data">
        <thead class="table-warning bg-gradient">
            <tr>
                <th class="text-center align-middle" style="width: 5%">NO</th>
                <th class="text-center align-middle">UNTUK</th>
                <th class="text-center align-middle">NO REK</th>
                <th class="text-center align-middle">ATAS NAMA</th>
                <th class="text-center align-middle">BANK</th>
                <th class="text-center align-middle">ACT</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $d)
            <tr>
                <td class="text-center align-middle">{{$loop->iteration}}</td>
                <td class="text-center align-middle">{{strtoupper($d->untuk)}}</td>
                <td class="text-center align-middle">{{$d->no_rek}}</td>
                <td class="text-center align-middle">{{$d->nama_rek}}</td>
                <td class="text-center align-middle">{{$d->bank}}</td>
                <td class="text-center align-middle">
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-primary m-2" data-bs-toggle="modal"
                            data-bs-target="#editRekening" onclick="editRekening({{$d}}, {{$d->id}})"><i
                                class="fa fa-edit"></i></button>
                    </div>

                </td>
            </tr>
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


    function editRekening(data, id) {
        document.getElementById('edit_untuk').value = data.untuk.toUpperCase();
        document.getElementById('edit_nama_rek').value = data.nama_rek;
        document.getElementById('edit_no_rek').value = data.no_rek;
        document.getElementById('edit_bank').value = data.bank;
        document.getElementById('editForm').action = '/db/rekening/' + id + '/update';
    };

    $('#data').DataTable({
        paging: false,
        scrollCollapse: true,
        scrollY: "550px",
    });


    var edit_no_rek = new Cleave('#edit_no_rek', {
        delimiter: '-',
        blocks: [4, 4, 8]
    });

    $('#editForm').submit(function(e){
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
                    $('#spinner').show();
                    this.submit();
                }
            })
        });

</script>
@endpush
