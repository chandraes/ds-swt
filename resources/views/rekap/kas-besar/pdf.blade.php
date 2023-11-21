@extends('layouts.doc-nologo-1')
@section('content')
<div class="container-fluid">
    <center>
        <h2>REKAP KAS BESAR</h2>
        <h2>{{$stringBulanNow}} {{$tahun}}</h2>
    </center>
</div>
<div class="container-fluid table-responsive ml-3 text-pdf">
    <div class="row mt-3">
        <table class="table table-hover table-bordered table-pdf text-pdf" id="rekapTable">
            <thead class=" table-success">
            <tr>
                <th class="text-center align-middle table-pdf text-pdf">Tanggal</th>
                <th class="text-center align-middle table-pdf text-pdf">Uraian</th>
                <th class="text-center align-middle table-pdf text-pdf">Kode Deposit</th>
                <th class="text-center align-middle table-pdf text-pdf">Titipan</th>
                <th class="text-center align-middle table-pdf text-pdf">Masuk</th>
                <th class="text-center align-middle table-pdf text-pdf">Keluar</th>
                <th class="text-center align-middle table-pdf text-pdf">Saldo</th>
                <th class="text-center align-middle table-pdf text-pdf">Transfer Ke Rekening</th>
                <th class="text-center align-middle table-pdf text-pdf">Bank</th>
                <th class="text-center align-middle table-pdf text-pdf">Modal Investor</th>
            </tr>
            <tr class="table-warning">
                <td colspan="6" class="text-center align-middle table-pdf text-pdf">Saldo Bulan
                    {{$stringBulan}} {{$tahunSebelumnya}}</td>
                <td class="text-center align-middle table-pdf text-pdf">Rp. {{$dataSebelumnya ? number_format($dataSebelumnya->saldo,
                    0, ',','.') : ''}}</td>
                <td class="table-pdf text-pdf"></td>
                <td class="table-pdf text-pdf"></td>
                <td class="text-center align-middle table-pdf text-pdf">Rp. {{$dataSebelumnya ?
                    number_format($dataSebelumnya->modal_investor_terakhir, 0,',','.') : ''}}</td>
            </tr>
            </thead>
            <tbody>
                @foreach ($data as $d)
                <tr>
                    <td class="text-center align-middle table-pdf text-pdf">{{$d->tanggal}}</td>
                    <td class="text-center align-middle table-pdf text-pdf">{{$d->uraian}}</td>
                    <td class="text-center align-middle table-pdf text-pdf">
                        {{$d->nomor_deposit != 00 ? $d->kode_deposit.$d->nomor_deposit : ''}}
                    </td>
                    <td class="text-center align-middle table-pdf text-pdf">
                        {{$d->nomor_titipan != 00 ? $d->kode_titipan.$d->nomor_titipan : ''}}
                    </td>
                    <td class="text-center align-middle table-pdf text-pdf">{{$d->jenis === 1 ?
                        number_format($d->nominal_transaksi, 0, ',', '.') : ''}}
                    </td>
                    <td class="text-center align-middle text-danger table-pdf text-pdf">{{$d->jenis === 0 ?
                        number_format($d->nominal_transaksi, 0, ',', '.') : ''}}
                    </td>
                    <td class="text-center align-middle table-pdf text-pdf">{{number_format($d->saldo, 0,',','.')}}</td>
                    <td class="text-center align-middle table-pdf text-pdf">{{$d->nama_rek}}</td>
                    <td class="text-center align-middle table-pdf text-pdf">{{$d->bank}}</td>
                    <td class="text-center align-middle table-pdf text-pdf">{{number_format($d->modal_investor, 0, ',', '.')}}</td>
                </tr>
                @endforeach
                <tr>
                    <td class="table-pdf text-pdf" style="height: 16px"></td>
                    <td class="table-pdf text-pdf"></td>
                    <td class="table-pdf text-pdf"></td>
                    <td class="table-pdf text-pdf"></td>
                    <td class="table-pdf text-pdf"></td>
                    <td class="table-pdf text-pdf"></td>
                    <td class="table-pdf text-pdf"></td>
                    <td class="table-pdf text-pdf"></td>
                    <td class="table-pdf text-pdf"></td>
                    <td class="table-pdf text-pdf"></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center align-middle table-pdf text-pdf" colspan="4"><strong>GRAND TOTAL</strong></td>
                    <td class="text-center align-middle table-pdf text-pdf"><strong>{{number_format($data->where('jenis',
                            1)->sum('nominal_transaksi'), 0, ',', '.')}}</strong></td>
                    <td class="text-center align-middle text-danger table-pdf text-pdf"><strong>{{number_format($data->where('jenis',
                            0)->sum('nominal_transaksi'), 0, ',', '.')}}</strong></td>
                    {{-- latest saldo --}}
                    <td class="text-center align-middle table-pdf text-pdf">
                        <strong>
                            {{$data->last() ? number_format($data->last()->saldo, 0, ',', '.') : ''}}
                        </strong>
                    </td>
                    <td class="table-pdf text-pdf"></td>
                    <td class="table-pdf text-pdf"></td>
                    <td class="text-center align-middle table-pdf text-pdf">
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
