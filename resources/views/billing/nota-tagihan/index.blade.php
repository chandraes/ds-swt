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
                    <th class="text-center align-middle" style="width:3%">
                        Select
                        <input type="checkbox" onclick="checkAll(this)" id="checkAll">
                    </th>
                    <th class="text-center align-middle">No</th>
                    <th class="text-center align-middle">Tanggal</th>
                    <th class="text-center align-middle">Supplier</th>
                    <th class="text-center align-middle">Nota Timbangan</th>
                    <th class="text-center align-middle">Berat</th>
                    <th class="text-center align-middle" style="width: 3%">Sat</th>
                    <th class="text-center align-middle">Harga Satuan</th>
                    <th class="text-center align-middle">Total Harga</th>
                    <th class="text-center align-middle">PPH 0,25%</th>
                    <th class="text-center align-middle">Total Tagihan</th>
                    <th class="text-center align-middle">Act</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $d)
                <tr>
                    <td class="text-center align-middle">
                        {{-- checklist on check push $d->id to $selectedData --}}
                        <input type="checkbox" value="{{$d->id}}" onclick="check(this, {{$d->id}})" id="idSelect-{{$d->id}}">
                    </td>
                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                    <td class="text-center align-middle">{{$d->tanggal}}</td>
                    <td class="text-center align-middle">{{$d->supplier->nickname}}</td>
                    <td class="text-center align-middle">{{$d->nota_timbangan}}</td>
                    <td class="text-center align-middle">{{$d->berat}}</td>
                    <td class="text-center align-middle">Kg</td>
                    <td class="text-center align-middle">{{$d->harga}}</td>
                    <td class="text-center align-middle">{{$d->total}}</td>
                    <td class="text-center align-middle">{{$d->pph}}</td>
                    <td class="text-center align-middle">{{$d->total_tagihan}}</td>
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
            <tfoot>
                <tr>
                    <th colspan="5" class="text-center align-middle">Grand Total</th>
                    <th class="text-center align-middle">{{$totalBerat}}</th>
                    <th class="text-center align-middle">Kg</th>
                    <th class="text-center align-middle"></th>
                    <th class="text-center align-middle">{{$total}}</th>
                    <th class="text-center align-middle"></th>
                    <th class="text-center align-middle">{{$totalTagihan}}</th>
                    <th class="text-center align-middle"></th>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="row mt-5">
        <input type="hidden" name="selectedData" required>
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

        function check(checkbox, id) {
                if (checkbox.checked) {
                    $('input[name="selectedData"]').val(function(i, v) {
                        // if end of string, remove comma
                        return v + id + ',';

                    });
                } else {
                    $('input[name="selectedData"]').val(function(i, v) {
                        // remove id from string
                        return v.replace(id + ',', '');
                    });
                }

                value = $('input[name="selectedData"]').val();

                if(value.slice(-1) == ','){
                    // remove comma from last number
                    value = value.slice(0, -1);
                }
                console.log(value);
            }

            // check all checkbox and push all id to $selectedData and check all checkbox
            function checkAll(checkbox) {
                if (checkbox.checked) {
                    $('input[name="selectedData"]').val(function(i, v) {
                        // if end of string, remove comma
                        @foreach ($data as $d)
                            v = v + {{$d->id}} + ',';
                            $('#idSelect-{{$d->id}}').prop('checked', true);
                        @endforeach
                        return v;
                    });
                } else {
                    $('input[name="selectedData"]').val(function(i, v) {
                        // remove id from string
                        @foreach ($data as $d)
                            v = v.replace({{$d->id}} + ',', '');
                            $('#idSelect-{{$d->id}}').prop('checked', false);
                        @endforeach
                        return v;
                    });
                }

                value = $('input[name="selectedData"]').val();

                if(value.slice(-1) == ','){
                    // remove comma from last number
                    value = value.slice(0, -1);
                }
                console.log(value);
            }

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
