<?php

namespace App\Http\Controllers;

use App\Models\KasBesar;
use App\Models\Rekening;
use App\Services\StarSender;
use App\Models\GroupWa;
use Illuminate\Http\Request;

class FormDepositController extends Controller
{
    public function masuk()
    {
        $rekening = Rekening::where('untuk', 'kas-besar')->first();

        return view('billing.form-deposit.masuk', [
            'rekening' => $rekening
        ]);
    }

    public function masuk_store(Request $request)
    {
        $data = $request->validate([
            'nominal_transaksi' => 'required',
        ]);

        $rekening = Rekening::where('untuk', 'kas-besar')->first();

        $data['tanggal'] = date('Y-m-d');
        $data['uraian'] = 'Deposit';
        $data['jenis'] = 1;
        $data['nominal_transaksi'] = str_replace('.', '', $data['nominal_transaksi']);
        $data['modal_investor'] = -$data['nominal_transaksi'];

        $kasBesar = new KasBesar();
        $lastKasBesar = $kasBesar->lastKasBesar();

        if ($lastKasBesar) {
            $data['saldo'] = $lastKasBesar->saldo + $data['nominal_transaksi'];
            $data['modal_investor_terakhir'] = $lastKasBesar->modal_investor_terakhir - $data['nominal_transaksi'];
        } else {
            $data['saldo'] = $data['nominal_transaksi'];
            $data['modal_investor_terakhir'] = $data['modal_investor'];
        }

        $data['no_rek'] = $rekening->no_rek;
        $data['nama_rek'] = $rekening->nama_rek;
        $data['bank'] = $rekening->bank;

        $store = KasBesar::create($data);

        $group = GroupWa::where('untuk', 'kas-besar')->first();
        $pesan =    "🔵🔵🔵🔵🔵🔵🔵🔵🔵\n".
                    "*Form Permintaan Deposit*\n".
                    "🔵🔵🔵🔵🔵🔵🔵🔵🔵\n\n".
                    "Nilai :  *Rp. ".number_format($data['nominal_transaksi'], 0, ',', '.')."*\n\n".
                    "Ditransfer ke rek:\n\n".
                    "Bank      : ".$data['bank']."\n".
                    "Nama    : ".$data['nama_rek']."\n".
                    "No. Rek : ".$data['no_rek']."\n\n".
                    "==========================\n".
                    "Sisa Saldo Kas Besar : \n".
                    "Rp. ".number_format($store->saldo, 0, ',', '.')."\n\n".
                    "Total Modal Investor : \n".
                    "Rp. ".number_format($store->modal_investor_terakhir, 0, ',', '.')."\n\n".
                    "Terima kasih 🙏🙏🙏\n";
        $send = new StarSender($group->nama_group, $pesan);
        $res = $send->sendGroup();

        return redirect()->route('billing')->with('success', 'Berhasil menambahkan data');
    }

    public function keluar()
    {
        $rekening = Rekening::where('untuk', 'withdraw')->first();

        return view('billing.form-deposit.keluar', [
            'rekening' => $rekening
        ]);
    }

    public function keluar_store(Request $request)
    {
        $data = $request->validate([
            'nominal_transaksi' => 'required',
        ]);

        $rekening = Rekening::where('untuk', 'withdraw')->first();

        $data['uraian'] = 'Withdraw';
        $data['nominal_transaksi'] = str_replace('.', '', $data['nominal_transaksi']);
        $data['jenis'] = 0;
        $data['tanggal'] = date('Y-m-d');
        $data['nama_rek'] = substr($rekening->nama_rek, 0, 15);
        $data['no_rek'] = $rekening->no_rek;
        $data['bank'] = $rekening->bank;

        $kasBesar = new KasBesar;
        $last = $kasBesar->lastKasBesar();

        if($last == null || $last->saldo < $data['nominal_transaksi']){

            return redirect()->back()->with('error', 'Saldo Kas Besar Tidak Cukup');

        }else{
            $data['saldo'] = $last->saldo - $data['nominal_transaksi'];
            $data['modal_investor'] = $data['nominal_transaksi'];
            $data['modal_investor_terakhir']= $last->modal_investor_terakhir + $data['nominal_transaksi'];
        }

        $store = KasBesar::create($data);

        $group = GroupWa::where('untuk', 'kas-besar')->first();

        $pesan =    "🔴🔴🔴🔴🔴🔴🔴🔴🔴\n".
                    "*Form Pengembalian Deposit*\n".
                    "🔴🔴🔴🔴🔴🔴🔴🔴🔴\n\n".
                    "Nilai :  *Rp. ".number_format($data['nominal_transaksi'], 0, ',', '.')."*\n\n".
                    "Ditransfer ke rek:\n\n".
                    "Bank      : ".$data['bank']."\n".
                    "Nama    : ".$data['nama_rek']."\n".
                    "No. Rek : ".$data['no_rek']."\n\n".
                    "==========================\n".
                    "Sisa Saldo Kas Besar : \n".
                    "Rp. ".number_format($store->saldo, 0, ',', '.')."\n\n".
                    "Total Modal Investor : \n".
                    "Rp. ".number_format($store->modal_investor_terakhir, 0, ',', '.')."\n\n".
                    "Terima kasih 🙏🙏🙏\n";
        $send = new StarSender($group->nama_group, $pesan);
        // $res = $send->sendGroup();

        return redirect()->route('billing')->with('success', 'Data berhasil disimpan');
    }
}
