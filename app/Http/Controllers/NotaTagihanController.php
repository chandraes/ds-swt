<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class NotaTagihanController extends Controller
{
    public function index(Customer $customer)
    {
        $transaksi = new Transaksi;
        
        $data = $transaksi->notaTagihan($customer->id);

        return view('billing.nota-tagihan.index', [
            'data' => $data,
            'customer' => $customer,
        ]);
    }
}
