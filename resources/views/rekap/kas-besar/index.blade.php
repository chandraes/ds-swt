@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12 text-center">
            <h1><u>REKAP KAS BESAR</u></h1>
            <h1>{{$stringBulanNow}} {{$tahun}}</h1>
        </div>
    </div>
    @include('swal')
    <div class="flex-row justify-content-between mt-3">
        <div class="col-md-6">
            <table class="table">
                <tr class="text-center">
                    <td><a href="{{route('home')}}"><img src="{{asset('images/dashboard.svg')}}" alt="dashboard"
                                width="30"> Dashboard</a></td>
                    <td><a href="{{route('rekap')}}"><img src="{{asset('images/rekap.svg')}}" alt="dokumen"
                                width="30"> REKAP</a></td>
                    <td>
                        <a href="{{route('rekap.kas-besar.print', ['bulan' => $bulan, 'tahun' => $tahun])}}" target="_blank"><img src="{{asset('images/print.svg')}}" alt="dokumen"
                            width="30"> PRINT PDF</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="container-fluid mt-5">
    <form action="{{route('rekap.kas-besar')}}" method="get">
        <div class="row">
            <div class="col-md-3 mb-3">
                <label for="bulan" class="form-label">Bulan</label>
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
            <div class="col-md-3 mb-3">
                <label for="tahun" class="form-label">Tahun</label>
                <select class="form-select" name="tahun" id="tahun">
                    @foreach ($dataTahun as $d)
                    <option value="{{$d->tahun}}" {{$d->tahun == $tahun ? 'selected' : ''}}>{{$d->tahun}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label for="tahun" class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary form-control" id="btn-cari">Tampilkan</button>
            </div>
            {{-- <div class="col-md-3 mb-3">
                <label for="showPrint" class="form-label">&nbsp;</label>
                <a href="{{route('rekap.kas-besar.preview', ['bulan' => $bulan, 'tahun' => $tahun])}}" target="_blank" class="btn btn-secondary form-control" id="btn-cari">Print Preview</a>
            </div> --}}
        </div>
    </form>
</div>
<div class="container-fluid table-responsive ml-3">
    <div class="row mt-3">
        <table class="table table-hover table-bordered" id="rekapTable">
            <thead class=" table-success">
            <tr>
                <th class="text-center align-middle">Tanggal</th>
                <th class="text-center align-middle">Uraian</th>
                <th class="text-center align-middle">Deposit</th>
                <th class="text-center align-middle">Titipan</th>
                <th class="text-center align-middle">Tagihan</th>
                <th class="text-center align-middle">Bayar</th>
                <th class="text-center align-middle">Masuk</th>
                <th class="text-center align-middle">Keluar</th>
                <th class="text-center align-middle">Saldo</th>
                <th class="text-center align-middle">Transfer Ke Rekening</th>
                <th class="text-center align-middle">Bank</th>
                <th class="text-center align-middle">Modal Investor</th>
            </tr>
            <tr class="table-warning">
                <td colspan="7" class="text-center align-middle">Saldo Bulan
                    {{$stringBulan}} {{$tahunSebelumnya}}</td>
                <td></td>
                <td class="text-center align-middle">Rp. {{$dataSebelumnya ? number_format($dataSebelumnya->saldo,
                    0, ',','.') : ''}}</td>
                <td></td>
                <td></td>
                <td class="text-center align-middle">Rp. {{$dataSebelumnya ?
                    number_format($dataSebelumnya->modal_investor_terakhir, 0,',','.') : ''}}</td>
            </tr>
            </thead>
            <tbody>
                @foreach ($data as $d)
                <tr>
                    <td class="text-center align-middle">{{$d->id_tanggal}}</td>
                    <td class="text-center align-middle">
                        @if ($d->invoice_tagihan_id)
                        <a href="{{route('rekap.kas-besar.detail-tagihan', ['invoice' => $d->invoice_tagihan_id])}}">{{$d->uraian}}</a>
                        @elseif($d->invoice_bayar_id)
                        <a href="{{route('rekap.kas-besar.detail-bayar', ['invoice' => $d->invoice_bayar_id])}}">{{$d->uraian}}</a>
                        @else
                        {{$d->uraian}}
                        @endif

                    </td>
                    <td class="text-center align-middle">
                        {{$d->nomor_deposit != 00 ? $d->kode_deposit.$d->nomor_deposit : ''}}
                    </td>
                    <td class="text-center align-middle">
                        {{$d->nomor_titipan != 00 ? $d->kode_titipan.$d->nomor_titipan : ''}}
                    </td>
                    <td class="text-center align-middle">
                        {{$d->nomor_tagihan != 00 ? $d->kode_tagihan.$d->nomor_tagihan : ''}}
                    </td>
                    <td class="text-center align-middle">
                        {{$d->nomor_bayar != 00 ? $d->kode_bayar.$d->nomor_bayar : ''}}
                    </td>
                    <td class="text-center align-middle">{{$d->jenis === 1 ?
                        number_format($d->nominal_transaksi, 0, ',', '.') : ''}}
                    </td>
                    <td class="text-center align-middle text-danger">{{$d->jenis === 0 ?
                        number_format($d->nominal_transaksi, 0, ',', '.') : ''}}
                    </td>
                    <td class="text-center align-middle">{{number_format($d->saldo, 0,',','.')}}</td>
                    <td class="text-center align-middle">{{$d->nama_rek}}</td>
                    <td class="text-center align-middle">{{$d->bank}}</td>
                    <td class="text-center align-middle">{{number_format($d->modal_investor, 0, ',', '.')}}</td>
                </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center align-middle" colspan="6"><strong>GRAND TOTAL</strong></td>
                    <td class="text-center align-middle"><strong>{{number_format($data->where('jenis',
                            1)->sum('nominal_transaksi'), 0, ',', '.')}}</strong></td>
                    <td class="text-center align-middle text-danger"><strong>{{number_format($data->where('jenis',
                            0)->sum('nominal_transaksi'), 0, ',', '.')}}</strong></td>
                    {{-- latest saldo --}}
                    <td class="text-center align-middle">
                        <strong>
                            {{number_format($data->where('jenis',
                            1)->sum('nominal_transaksi') - $data->where('jenis',
                            0)->sum('nominal_transaksi') + ($dataSebelumnya ? $dataSebelumnya->saldo : 0), 0,',','.')}}
                        </strong>
                    </td>
                    <td></td>
                    <td></td>
                    <td class="text-center align-middle">
                        <strong>
                            {{$data->last() ? number_format($data->last()->modal_investor_terakhir, 0, ',', '.') : ''}}
                        </strong>
                    </td>
                </tr>
            </tfoot>
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
            "ordering": false,
            "searching": false,
            "scrollCollapse": true,
            "scrollY": "550px",
            "fixedColumns": {
                "leftColumns": 4,
                "rightColumns": 2
            },

        });

    });

</script>
@endpush
