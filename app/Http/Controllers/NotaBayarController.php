<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Supplier;
use App\Models\KasBesar;
use App\Models\KasSupplier;
use App\Models\InvoiceBayar;
use App\Models\InvoiceBayarDetail;
use App\Models\GroupWa;
use App\Services\StarSender;
use Illuminate\Support\Facades\DB;
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

    public function cutoff(Supplier $supplier, Request $request)
    {
        $data = $request->validate([
            'total_bayar' => 'required|integer',
        ]);

        $invoiceBayar = new InvoiceBayar;

        $data['supplier_id'] = $supplier->id;

        $kasSupplier = new KasSupplier;
        $kasBesar = new KasBesar;
        $transaksi = new Transaksi;

        $saldoKasBesar = $kasBesar->lastKasBesar()->saldo;

        if ($saldoKasBesar < $data['total_bayar']) {
            return redirect()->back()->with('error', 'Saldo kas besar tidak cukup');
        }

        DB::beginTransaction();

        $invoice = $invoiceBayar->insertBayar($data);

        $k['uraian'] = 'Pembayaran BS' . $invoice->no_invoice;
        $k['nominal_transaksi'] = $data['total_bayar'];
        $k['supplier_id'] = $invoice->supplier_id;
        $k['invoice_bayar_id'] = $invoice->id;
        $storeKasSupplier = $kasSupplier->insertBayar($k);

        $b['nominal_transaksi'] = $data['total_bayar'];
        $b['supplier_id'] = $invoice->supplier_id;
        $b['nomor_bayar'] = $invoice->no_invoice;
        $b['invoice_bayar_id'] = $invoice->id;

        $store = $kasBesar->insertBayar($b);

        $list = $transaksi->notaBayar($supplier->id);
        // get id list to array
        $listId = $list->pluck('id')->toArray();

        foreach ($listId as $k => $value) {
            $detail = InvoiceBayarDetail::create([
                'invoice_bayar_id' => $invoice->id,
                'transaksi_id' => $value,
            ]);

            $t = Transaksi::where('id', $value)->update([
                'bayar' => 1,
            ]);
        }

        $group = GroupWa::where('untuk', 'kas-besar')->first();

        $pesan =    "🔴🔴🔴🔴🔴🔴🔴🔴🔴\n".
                    "*Form Pembayaran Supplier*\n".
                    "🔴🔴🔴🔴🔴🔴🔴🔴🔴\n\n".
                    "*BS".$store->nomor_bayar."*\n\n".
                    "Supplier : ".$invoice->supplier->nickname."\n\n".
                    "Nilai :  *Rp. ".number_format($store->nominal_transaksi, 0, ',', '.')."*\n\n".
                    "Ditransfer ke rek:\n\n".
                    "Bank      : ".$store->bank."\n".
                    "Nama    : ".$store->nama_rek."\n".
                    "No. Rek : ".$store->no_rek."\n\n".
                    "==========================\n".
                    "Sisa Saldo Supplier: \n".
                    "Rp. ".number_format($storeKasSupplier->saldo, 0, ',', '.')."\n\n".
                    "Sisa Saldo Kas Besar : \n".
                    "Rp. ".number_format($store->saldo, 0, ',', '.')."\n\n".
                    "Total Modal Investor : \n".
                    "Rp. ".number_format($store->modal_investor_terakhir, 0, ',', '.')."\n\n".
                    "Terima kasih 🙏🙏🙏\n";
        $send = new StarSender($group->nama_group, $pesan);
        $res = $send->sendGroup();

        DB::commit();

        return redirect()->route('billing')->with('success', 'Berhasil menambahkan pembayaran supplier');
    }
}
