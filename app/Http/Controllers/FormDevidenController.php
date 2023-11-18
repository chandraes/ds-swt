<?php

namespace App\Http\Controllers;

use App\Models\KasBesar;
use App\Models\Investor;
use App\Models\Rekening;
use App\Models\GroupWa;
use Illuminate\Http\Request;
use App\Services\StarSender;
use Carbon\Carbon;

class FormDevidenController extends Controller
{
    public function index()
    {
        $investors = Investor::all();
        $totalPersentase = $investors->sum('persentase');

        if ($totalPersentase != 100) {
            return redirect()->route('billing')->with('error', 'Total persentase investor belum mencapai 100%');
        }

        return view('billing.deviden.index', [
            'data' => $investors
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nominal_transaksi' => 'required',
        ]);

        $data['nominal_transaksi'] = str_replace('.', '', $data['nominal_transaksi']);


        $last = KasBesar::latest()->first();


        if ($last == null || $last->saldo < $data['nominal_transaksi']) {
            return redirect()->route('billing.deviden.index')->with('error', 'Saldo tidak cukup');
        }

        $investor = Investor::all();
        $group = GroupWa::where('untuk', 'kas-besar')->first();
        $month = Carbon::now()->locale('id')->monthName;

        $isiPesan = [];

        foreach ($investor as $v) {
            usleep(50000);

            $last2 = KasBesar::latest()->orderBy('id', 'desc')->first();
            $nilai2 = $data['nominal_transaksi'] * $v->persentase / 100;
            // $rekening = Rekening::where('untuk', 'withdraw')->first();

                $k['tanggal'] = date('Y-m-d');
                $k['jenis'] = 0;
                $k['uraian'] = "Bagi Deviden ".$v->nama;
                $k['nominal_transaksi'] = $nilai2;
                $k['saldo'] = $last2->saldo - $nilai2;
                $k['nama_rek'] = substr($v->nama_rek, 0, 15);
                $k['bank'] = $v->bank;
                $k['no_rek'] = $v->no_rek;
                $k['modal_investor_terakhir'] = $last2->modal_investor_terakhir;

            $store = KasBesar::create($k);
            //  dd($store);

            $pesan = "🔴🔴🔴🔴🔴🔴🔴🔴🔴\n".
                    "*Form Deviden ".$month."*\n".
                    "🔴🔴🔴🔴🔴🔴🔴🔴🔴\n\n".
                    "Nama  : ".$v->nama."\n".
                    "Nilai :  *Rp. ".number_format($k['nominal_transaksi'], 0, ',', '.')."*\n\n".
                    "Ditransfer ke rek:\n\n".
                    "Bank      : ".$k['bank']."\n".
                    "Nama    : ".$k['nama_rek']."\n".
                    "No. Rek : ".$k['no_rek']."\n\n".
                    "==========================\n".
                    "Sisa Saldo Kas Besar : \n".
                    "Rp. ".number_format($store->saldo, 0, ',', '.')."\n\n".
                    "Total Modal Investor : \n".
                    "Rp. ".number_format($store->modal_investor_terakhir, 0, ',', '.')."\n\n".
                    "Terima kasih 🙏🙏🙏\n";

            array_push($isiPesan, $pesan);
        }

        // looping $isiPesan
        foreach ($isiPesan as $pesan) {
            $send = new StarSender($group->nama_group, $pesan);
            $res = $send->sendGroup();
        }

        return redirect()->route('billing')->with('success', 'Data berhasil disimpan');
    }

}
