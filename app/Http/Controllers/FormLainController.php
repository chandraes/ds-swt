<?php

namespace App\Http\Controllers;

use App\Models\Rekening;
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
        
    }

    public function keluar()
    {

    }

    public function keluar_store()
    {

    }
}
