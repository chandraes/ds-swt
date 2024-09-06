<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\InvoicePpn;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class PajakController extends Controller
{
    public function index()
    {
        $transaksi = new Transaksi();
        $ip = $transaksi->totalInvoicePpn();
        $ppn = InvoicePpn::where('bayar', false)->count();
        $t = $transaksi->where('status', 1)->get();
        $customer = Customer::all();
        return view('pajak.index', [
            'customer' => $customer,
            'ip' => $ip,
            'ppn' => $ppn,
            't' => $t,
        ]);
    }
}
