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
        $totalBerat = $transaksi->notaTransaksiBerat($customer->id);
        $total = $transaksi->notaTransaksiTotal($customer->id);
        $totalTagihan = $transaksi->notaTransaksiTotalTagihan($customer->id);
        $data = $transaksi->notaTagihan($customer->id);

        return view('billing.nota-tagihan.index', [
            'data' => $data,
            'customer' => $customer,
            'totalBerat' => $totalBerat,
            'total' => $total,
            'totalTagihan' => $totalTagihan,
        ]);
    }
}
