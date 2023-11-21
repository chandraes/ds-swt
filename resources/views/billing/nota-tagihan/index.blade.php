@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mb-5">
        <div class="col-md-12 text-center">
            <h1><u>NOTA TAGIHAN</u></h1>
            <h1>{{$customer->nama}}</h1>
        </div>
    </div>
        <div class="row mt-3">
        <table class="table table-bordered table-hover" id="tableTransaksi">
            <thead class="table-success">
                <tr>
                    <th class="text-center align-middle">No</th>
                    <th class="text-center align-middle">Tanggal</th>
                    <th class="text-center align-middle">Supplier</th>
                    <th class="text-center align-middle">Nota Timbangan</th>
                    <th class="text-center align-middle">Berat</th>
                    <th class="text-center align-middle">Sat</th>
                    <th class="text-center align-middle">Harga Satuan</th>
                    <th class="text-center align-middle">Total Harga</th>
                    <th class="text-center align-middle">PPH 0,25%</th>
                    <th class="text-center align-middle">Profit 1%</th>
                    <th class="text-center align-middle">Act</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $d)
                <tr>
                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                    <td class="text-center align-middle">{{$d->tanggal}}</td>
                    <td class="text-center align-middle">{{$d->supplier->nama}}</td>
                    <td class="text-center align-middle">{{$d->nota_timbangan}}</td>
                    <td class="text-center align-middle">{{$d->berat}}</td>
                    <td class="text-center align-middle">Kg</td>
                    <td class="text-center align-middle">{{$d->harga}}</td>
                    <td class="text-center align-middle">{{$d->total}}</td>
                    <td class="text-center align-middle">{{$d->pph}}</td>
                    <td class="text-center align-middle">{{$d->profit}}</td>
                    <td class="text-center align-middle">
                        {{-- delete  --}}
                        <form action="{{route('form-transaksi.delete', ['transaksi' => $d->id])}}" method="post" id="delete-{{$d->id}}">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <script>
                    $(document).ready(function(){
                        $('#delete-{{$d->id}}').submit(function(e){
                            e.preventDefault();
                            Swal.fire({
                                title: 'Apakah anda yakin?',
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
                    });
                </script>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="row mt-5">
        {{-- button lanjutkan --}}
            <form action="{{route('form-transaksi.lanjutkan', ['customer' => $customer->id])}}" method="post" id="lanjutkanForm">
                @csrf
                <button type="submit" class="btn btn-success form-control" id="lanjutkan">Lanjutkan</button>
            </form>
            {{-- button back --}}
            <div class="col-md-12">
                <a href="{{route('billing')}}" class="btn btn-secondary form-control mt-3">Kembali</a>
            </div>

    </div>
</div>
@endsection
@push('css')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="{{asset('assets/css/dt.min.css')}}" rel="stylesheet">
@endpush
@push('js')
<script src="{{asset('assets/js/dt5.min.js')}}"></script>
    <script src="{{asset('assets/js/cleave.min.js')}}"></script>
    <script>

        $(document).ready(function() {
                $('#tableTransaksi').DataTable({
                    "paging": false,
                    "searching": false,
                    "scrollCollapse": true,
                    "scrollY": "550px",

                });

            });

        $( function() {
            $( "#tanggal" ).datepicker({
                dateFormat: "dd-mm-yy"
            });
        });

         var nominal = new Cleave('#berat', {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand',
            numeralDecimalMark: ',',
            delimiter: '.'
        });
        // masukForm on submit, sweetalert confirm
        $('#masukForm').submit(function(e){
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

        $('#lanjutkanForm').submit(function(e){
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
