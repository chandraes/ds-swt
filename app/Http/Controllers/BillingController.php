<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Transaksi;
use App\Models\Supplier;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index()
    {
        $transaksi = new Transaksi();
        $nt = $transaksi->totalNotaTagihan();
        $nb = $transaksi->totalNotaBayar();
        $customer = Customer::all();
        $supplier = Supplier::select('id', 'nama', 'nickname')->get();

        return view('billing.index', [
            'customer' => $customer,
            'nt' => $nt,
            'nb' => $nb,
            'supplier' => $supplier
        ]);
    }
}
