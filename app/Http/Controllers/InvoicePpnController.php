<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Customer;
use App\Models\InvoicePpn;
use Illuminate\Http\Request;

class InvoicePpnController extends Controller
{
    public function index(Request $request, Customer $customer)
    {
        $transaksi = new Transaksi;
        $data = $transaksi->notaInvoice($customer->id);
        $totalBerat = $data->sum('berat');
        $total = $data->sum('total');
        $totalPPN = $data->sum('total_ppn');
        $totalTagihan = $data->sum('total_tagihan');
        $totalProfit = $data->sum('profit');
        $totalPPH = $data->sum('pph');

        return view('billing.invoice-ppn.index', [
            'data' => $data,
            'customer' => $customer,
            'totalBerat' => $totalBerat,
            'total' => $total,
            'totalPPN' => $totalPPN,
            'totalTagihan' => $totalTagihan,
            'totalProfit' => $totalProfit,
            'totalPPH' => $totalPPH,
        ]);
    }

    public function cutoff(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'selectedData' => 'required',
            'total_ppn' => 'required',
        ]);
    }
}
