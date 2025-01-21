@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mb-3">
        <div class="col-md-12 text-center">
            <h1><u>DETAIL INVOICE</u></h1>
            {{-- <h1>{{strtoupper($stringBulanNow)}} {{$tahun}}</h1>
            <h1>{{$customer->nama}}</h1> --}}
        </div>
        <div class="flex-row justify-content-between mt-3">
            <div class="col-md-6">
                <table class="table">
                    <tr class="text-center">
                        <td><a href="{{route('home')}}"><img src="{{asset('images/dashboard.svg')}}" alt="dashboard"
                                    width="30"> Dashboard</a></td>
                        <td><a href="{{route('pajak.index')}}"><img src="{{asset('images/pajak.svg')}}" alt="dokumen"
                                    width="30"> Pajak</a></td>
                        <td><a href="{{route('pajak.rekap-ppn')}}"><img src="{{asset('images/back.svg')}}" alt="dokumen"
                            width="30"> Back</a></td>

                    </tr>
                </table>
            </div>
        </div>
    </div>

    <hr>
    <div class="row mt-3">
        <table class="table table-bordered table-hover" id="tableTransaksi">
            <thead class="table-success">
                <tr>
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
                    <th class="text-center align-middle">Total PPN</th>
                </tr>
            </thead>
            @php
                $totalBerat = 0;
                $total = 0;
                $totalPPH = 0;
                $totalProfit = 0;
                $totalTagihan = 0;
                $totalBayar = 0;
                $totalPPN = 0;
            @endphp
            <tbody>
                @foreach ($data as $d)
                <tr>
                    <td class="text-center align-middle"></td>
                    <td class="text-center align-middle">{{$d->transaksi->id_tanggal}}</td>
                    <td class="text-center align-middle">{{$d->transaksi->supplier->nickname}}</td>
                    <td class="text-center align-middle">{{$d->transaksi->nota_timbangan}}</td>
                    <td class="text-center align-middle">{{$d->transaksi->nf_berat}}</td>
                    <td class="text-center align-middle">Kg</td>
                    <td class="text-end align-middle">{{$d->transaksi->nf_harga}}</td>
                    <td class="text-end align-middle">{{$d->transaksi->nf_total}}</td>
                    <td class="text-end align-middle">{{$d->transaksi->nf_pph}}</td>
                    <td class="text-end align-middle">{{number_format($d->transaksi->total_tagihan,0,',','.')}}</td>
                    <td class="text-end align-middle">{{number_format($d->transaksi->total_ppn,0,',','.')}}</td>

                </tr>
                @php
                    $totalBerat += $d->transaksi->berat;
                    $total += $d->transaksi->total;
                    $totalPPH += $d->transaksi->pph;
                    $totalProfit += $d->transaksi->profit;
                    $totalTagihan += $d->transaksi->total_tagihan;
                    $totalPPN += $d->transaksi->total_ppn;
                @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-center align-middle">Grand Total</th>
                    <th class="text-center align-middle">{{number_format($totalBerat, 0, ',','.')}}</th>
                    <th class="text-center align-middle">Kg</th>
                    <th class="text-center align-middle"></th>
                    <th class="text-end align-middle">{{number_format($total, 0,',','.')}}</th>
                    <th class="text-end align-middle">{{number_format($totalPPH, 0,',','.')}}</th>
                    <th class="text-end align-middle">{{number_format($totalTagihan, 0, ',','.')}}</th>
                    <th class="text-end align-middle">{{number_format($totalPPN, 0, ',','.')}}</th>
                </tr>
               
            </tfoot>
        </table>
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
                "scrollCollapse": true,
                "scrollY": "500px",

            });

            table.on( 'order.dt search.dt', function () {
                table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();
        });

</script>
@endpush
