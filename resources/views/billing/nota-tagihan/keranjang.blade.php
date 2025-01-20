@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mb-5">
        <div class="col-md-12 text-center">
            <h1><u>KERANJANG NOTA TAGIHAN</u></h1>
            <h1>{{$customer->nama}}</h1>
        </div>
    </div>
    {{-- if has any error --}}
    @if ($errors->any())
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Whoops!</strong> Ada kesalahan dalam input data:
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </ul>
            </div>
        </div>
    </div>
    @endif
    {{-- end if --}}
    {{-- <div class="row">
        <div class="col-md-6">
            <label for="berat" class="form-label">Total Tagihan di Pilih</label>
            <div class="input-group">
                <span class="input-group-text" id="basic-addon1">Rp.</span>
                <input type="text" class="form-control" id="total_tagih_display" disabled >
            </div>
        </div>
    </div> --}}
    <div class="row mt-3">
        <table class="table table-bordered table-hover" id="tableTransaksi">
            <thead class="table-success">
                <tr>
                    {{-- <th class="text-center align-middle" style="width:3%">
                        Select
                        <input type="checkbox" onclick="checkAll(this)" id="checkAll">
                    </th> --}}
                    <th class="text-center align-middle">No</th>
                    <th class="text-center align-middle">Tanggal</th>
                    <th class="text-center align-middle">Supplier</th>
                    <th class="text-center align-middle">Nota Timbangan</th>
                    <th class="text-center align-middle">Berat</th>
                    <th class="text-center align-middle" style="width: 3%">Sat</th>
                    <th class="text-center align-middle">Harga Satuan</th>
                    <th class="text-center align-middle">Total Harga</th>
                    <th class="text-center align-middle">PPH 0,25%</th>
                    <th class="text-center align-middle">Profit</th>
                    <th class="text-center align-middle">Total Tagihan</th>
                    <th class="text-center align-middle">Act</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $d)
                <tr>
                    <td class="text-center align-middle"></td>
                    <td class="text-center align-middle">{{$d->id_tanggal}}</td>
                    <td class="text-center align-middle">{{$d->supplier->nickname}}</td>
                    <td class="text-center align-middle">{{$d->nota_timbangan}}</td>
                    <td class="text-center align-middle">{{$d->nf_berat}}</td>
                    <td class="text-center align-middle">Kg</td>
                    <td class="text-center align-middle">{{$d->nf_harga}}</td>
                    <td class="text-center align-middle">{{$d->nf_total}}</td>
                    <td class="text-center align-middle">{{$d->nf_pph}}</td>
                    <td class="text-center align-middle">{{$d->nf_profit}}</td>
                    <td class="text-center align-middle">{{number_format($d->total_tagihan,0,',','.')}}</td>
                    <td class="text-center align-middle">
                        <form action="{{route('nota-tagihan.keranjang.delete', ['transaksi' => $d->id])}}" method="post" style="display: inline-block;"
                            id="delete-{{$d->id}}">
                            @csrf
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
                                    $('#spinner').show();
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
                    <th colspan="4" class="text-center align-middle">Grand Total</th>
                    <th class="text-center align-middle">{{number_format($data->sum('berat'), 0, ',','.')}}</th>
                    <th class="text-center align-middle">Kg</th>
                    <th class="text-center align-middle"></th>
                    <th class="text-center align-middle">{{number_format($data->sum('total'), 0, ',','.')}}</th>
                    <th class="text-center align-middle">{{number_format($data->sum('pph'), 0, ',','.')}}</th>
                    <th class="text-center align-middle">{{number_format($data->sum('profit'), 0, ',','.')}}</th>
                    <th class="text-center align-middle">{{number_format($data->sum('total_tagihan'), 0, ',','.')}}</th>
                    <th class="text-center align-middle"></th>
                </tr>
            </tfoot>
        </table>
    </div>
    <hr>
    <div class="row mt-3">
        <form action="{{route('nota-tagihan.keranjang.lanjutkan', ['customer' => $customer->id])}}" method="post" id="masukForm">
        @csrf

        <div class="row mb-4 p-3">
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="" class="form-label">Total DPP</label>
                    <input
                        type="text"
                        class="form-control"
                        name="dpp"
                        id="dpp" disabled value="{{number_format($data->sum('total'), 0, ',','.')}}"
                    />
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="" class="form-label">Total PPH</label>
                    <input
                        type="text"
                        class="form-control"
                        name="dpp"
                        id="dpp" disabled value="{{number_format($data->sum('pph'), 0, ',','.')}}"
                    />
                </div>
            </div>
            @if ($customer->ppn_kumulatif == 0)
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="" class="form-label">Total PPN</label>
                    <input
                        type="text"
                        class="form-control"
                        name="dpp"
                        id="dpp" disabled value="{{number_format($data->sum('total_ppn'), 0, ',','.')}}"
                    />
                </div>
            </div>
            @endif
            @php
                $gt = $customer->ppn_kumulatif == 0 ? $data->sum('total_tagihan')+$data->sum('total_ppn') : $data->sum('total_tagihan');
            @endphp
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="" class="form-label">Grand Total</label>
                    <input
                        type="text"
                        class="form-control"
                        name="gt"
                        id="gt" disabled value="{{number_format($gt, 0, ',','.')}}"
                    />
                </div>
            </div>
        </div>


            <div class="col-md-12">
                <button type="submit" class="btn btn-primary form-control"><i class="fa fa-credit-card pe-1"></i> Simpan</button>
            </div>
        </form>
        <div class="col-md-12">
            <a href="{{route('nota-tagihan.index', ['customer' => $customer])}}" class="btn btn-secondary form-control mt-3">Kembali</a>
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
            var table = $('#tableTransaksi').DataTable({
                "paging": false,
                "searching": false,
                "scrollCollapse": true,
                "scrollY": "500px",

            });

            table.on( 'order.dt search.dt', function () {
                table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();
        });

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
                    $('#spinner').show();
                    this.submit();
                }
            })
        });

        $('#lanjutkanForm').submit(function(e){
            e.preventDefault();
            Swal.fire({
                title: 'Apakah data sudah benar?',
                text: "Total Tagihan Rp. "+value,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, masukan keranjang!'
                }).then((result) => {
                if (result.isConfirmed) {
                    $('#spinner').show();
                    this.submit();
                }
            })
        });
</script>
@endpush
