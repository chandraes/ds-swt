<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Supplier;
use Illuminate\Http\Request;

class NotaBayarController extends Controller
{
    public function index(Request $request)
    {
        $supplier = Supplier::findOrFail($request->supplier_id);
        $transaksi = new Transaksi;
        $data = $transaksi->notaBayar($supplier->id);
        $totalBerat = $transaksi->notaBayarBerat($supplier->id);
        $total = $transaksi->notaBayarTotal($supplier->id);
        $totalBayar = $transaksi->notaBayarTotalBayar($supplier->id);

        return view('billing.nota-bayar.index', [
            'data' => $data,
            'supplier' => $supplier,
            'totalBerat' => $totalBerat,
            'total' => $total,
            'totalBayar' => $totalBayar,
        ]);
    }
}
