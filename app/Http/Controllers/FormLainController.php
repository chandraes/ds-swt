<?php

namespace App\Http\Controllers;

use App\Models\Rekening;
use App\Models\KasBesar;
use Illuminate\Http\Request;

class FormLainController extends Controller
{
    public function masuk()
    {
        $rekening = Rekening::where('untuk', 'kas-besar')->first();

        return view('billing.lain-lain.masuk', [
            'rekening' => $rekening
        ]);
    }

    public function masuk_store(Request $request)
    {
        $data = $request->validate([
            'uraian' => 'required',
            'nominal_transaksi' => 'required',
        ]);

        $data['tanggal'] = date('Y-m-d');
        $data['nominal_transaksi'] = str_replace('.', '', $data['nominal_transaksi']);

        $kas = new KasBesar;
        $rekening = Rekening::where('untuk', 'kas-besar')->first();
        $lastKasBesar = $kas->lastKasBesar();


        if ($lastKasBesar == null || $lastKasBesar->saldo < $data['nominal_transaksi']) {
            return redirect()->back()->with('error', 'Saldo Kas Besar Tidak Cukup!!');
        }
        $data['modal_investor_terakhir'] = $lastKasBesar->modal_investor_terakhir;
        $data['saldo'] = $lastKasBesar->saldo + $data['nominal_transaksi'];
        $data['jenis'] = 1;
        $data['no_rek'] = $rekening->no_rek;
        $data['nama_rek'] = $rekening->nama_rek;
        $data['bank'] = $rekening->bank;

        $store = KasBesar::create($data);

        return redirect()->route('billing')->with('success', 'Data Berhasil Ditambahkan');

    }

    public function keluar()
    {
        return view('billing.lain-lain.keluar');
    }

    public function keluar_store()
    {

    }
}
