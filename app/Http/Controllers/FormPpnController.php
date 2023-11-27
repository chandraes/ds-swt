<?php

namespace App\Http\Controllers;

use App\Models\InvoicePpn;
use Illuminate\Http\Request;

class FormPpnController extends Controller
{
    public function index()
    {
        $data = InvoicePpn::where('bayar', false)->get();
        return view('billing.form-ppn.index', [
            'data' => $data
        ]);
    }

    public function bayar(InvoicePpn $invoice)
    {
        $invoice->update([
            'bayar' => true
        ]);

        return redirect()->route('billing')->with('success', 'Berhasil bayar ppn');
    }
}