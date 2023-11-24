@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12 text-center">
            <h1>{{$customer->nama}}</h1>
        </div>
    </div>
    @include('swal')
    <div class="container-fluid mt-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <table class="table">
                    <tr class="text-center">
                        <td class="text-center align-middle"><a href="{{route('home')}}"><img src="{{asset('images/dashboard.svg')}}" alt="dashboard"
                                    width="30"> Dashboard</a></td>
                        <td class="text-center align-middle"><a href="{{route('rekap')}}"><img src="{{asset('images/rekap.svg')}}" alt="dokumen"
                                    width="30"> REKAP</a></td>
                        <td class="text-center align-middle">
                            <a href="{{route('statistik.print', ['customer' => $customer->id,'bulan' => $bulan, 'tahun' => $tahun])}}" target="_blank"><img src="{{asset('images/print.svg')}}" alt="dokumen"
                                width="30"> PRINT PDF</a>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <form action="{{route('statistik.index', $customer->id)}}" method="get">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <select class="form-select" name="bulan" id="bulan">
                                <option value="1" {{$bulan=='01' ? 'selected' : '' }}>Januari</option>
                                <option value="2" {{$bulan=='02' ? 'selected' : '' }}>Februari</option>
                                <option value="3" {{$bulan=='03' ? 'selected' : '' }}>Maret</option>
                                <option value="4" {{$bulan=='04' ? 'selected' : '' }}>April</option>
                                <option value="5" {{$bulan=='05' ? 'selected' : '' }}>Mei</option>
                                <option value="6" {{$bulan=='06' ? 'selected' : '' }}>Juni</option>
                                <option value="7" {{$bulan=='07' ? 'selected' : '' }}>Juli</option>
                                <option value="8" {{$bulan=='08' ? 'selected' : '' }}>Agustus</option>
                                <option value="9" {{$bulan=='09' ? 'selected' : '' }}>September</option>
                                <option value="10" {{$bulan=='10' ? 'selected' : '' }}>Oktober</option>
                                <option value="11" {{$bulan=='11' ? 'selected' : '' }}>November</option>
                                <option value="12" {{$bulan=='12' ? 'selected' : '' }}>Desember</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <select class="form-select" name="tahun" id="tahun">
                                @foreach ($dataTahun as $d)
                                <option value="{{$d->tahun}}" {{$d->tahun == $tahun ? 'selected' : ''}}>{{$d->tahun}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <button type="submit" class="btn btn-primary form-control" id="btn-cari">Tampilkan</button>
                        </div>
                        {{-- <div class="col-md-3 mb-3">
                            <label for="showPrint" class="form-label">&nbsp;</label>
                            <a href="{{route('rekap.kas-besar.preview', ['bulan' => $bulan, 'tahun' => $tahun])}}" target="_blank" class="btn btn-secondary form-control" id="btn-cari">Print Preview</a>
                        </div> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid table-responsive mt-2">
    <div class="row text-center">
        <h1>Statistik {{$nama_bulan}} {{$tahun}}</h1>
    </div>

    <div class="row mt-3">
        <table class="table table-bordered table-hover" id="rekapTable">
            <thead class="table-success">
                <tr>
                    <th class="text-center align-middle" style="width: 10%">Tanggal</th>
                    <th class="text-center align-middle">Berat (Kg)</th>
                    <th class="text-center align-middle">Belanja</th>
                    <th class="text-center align-middle">Tagihan</th>
                    <th class="text-center align-middle">Profit</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($statistics_monthly as $day => $data)
                    <tr>
                        <td class="text-center align-middle">{{ $day }}</td>
                        <td class="text-center align-middle">{{ number_format($data['total_berat'], 0, ',','.') }}</td>
                        <td class="text-center align-middle">{{ number_format($data['total_bayar'], 0, ',','.') }}</td>
                        <td class="text-center align-middle">{{ number_format($data['total_tagihan'], 0,',','.') }}</td>
                        <td class="text-center align-middle">{{ $data['total_profit'] }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-center align-middle">Grand Total</th>
                    <th class="text-center align-middle">{{ number_format($grand_total_berat, 0, ',','.') }}</th>
                    <th class="text-center align-middle">{{ number_format($grand_total_bayar, 0, ',','.') }}</th>
                    <th class="text-center align-middle">{{ number_format($grand_total_tagihan, 0, ',','.') }}</th>
                    <th class="text-center align-middle">{{ $grand_total_profit }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <br>
    <div class="row mt-3 text-center">
        <h1>Statistik {{$tahun}}</h1>
        <table class="table table-hover table-bordered" id="rekapTahunan">
            <thead class="table-success">
                <tr>
                    <th class="text-center align-middle">Bulan</th>
                    @for ($i = 1; $i <= 12; $i++)
                        <th class="text-center align-middle">{{$i}}</th> <!-- Month name -->
                    @endfor
                    <th class="text-center align-middle">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center align-middle">Berat (Kg)</td>
                    @foreach ($statistics_yearly as $data)
                        <td class="text-center align-middle">{{ number_format($data['total_berat'], 0,',','.') }}</td>
                    @endforeach
                    <td class="text-center align-middle">{{ number_format($yearly_total_berat, 0,',','.') }}</td>
                </tr>
                <tr>
                    <td class="text-center align-middle">Bayar</td>
                    @foreach ($statistics_yearly as $data)
                        <td class="text-center align-middle">{{ number_format($data['total_bayar'], 0,',','.') }}</td>
                    @endforeach
                    <td class="text-center align-middle">{{ number_format($yearly_total_bayar, 0,',','.') }}</td>
                </tr>
                <tr>
                    <td class="text-center align-middle">Tagihan</td>
                    @foreach ($statistics_yearly as $data)
                        <td class="text-center align-middle">{{ number_format($data['total_tagihan'], 0,',','.') }}</td>
                    @endforeach
                    <td class="text-center align-middle">{{ number_format($yearly_total_tagihan, 0,',','.') }}</td>
                </tr>
                <tr>
                    <td class="text-center align-middle">Profit</td>
                    @foreach ($statistics_yearly as $data)
                        <td class="text-center align-middle">{{ $data['total_profit'] }}</td>
                    @endforeach
                    <td class="text-center align-middle">{{ number_format($yearly_total_profit, 0,',','.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
@push('css')
<link href="{{asset('assets/css/dt.min.css')}}" rel="stylesheet">
@endpush
@push('js')
<script src="{{asset('assets/plugins/date-picker/date-picker.js')}}"></script>
<script src="{{asset('assets/js/dt5.min.js')}}"></script>
<script>

    $(document).ready(function() {
        $('#rekapTable').DataTable({
            "paging": false,
            "info": false,
            "ordering": false,
            "searching": false,
            "scrollCollapse": true,
            "scrollY": "400px",
        });

        $('#rekapTahunan').DataTable({
            "paging": false,
            "info": false,
            "ordering": false,
            "searching": false,
            "scrollCollapse": true,
            "scrollY": "450px",
        });

    });

</script>
@endpush
