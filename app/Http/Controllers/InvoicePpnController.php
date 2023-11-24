<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Customer;
use App\Models\InvoicePpn;
use App\Models\InvoicePpnDetail;
use App\Models\KasBesar;
use App\Models\GroupWa;
use App\Services\StarSender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoicePpnController extends Controller
{
    public function index(Request $request, Customer $customer)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        // nama bulan indonesia
        $stringBulan = \Carbon\Carbon::createFromDate($tahun, $bulan)->locale('id')->monthName;

        $transaksi = new Transaksi;
        $data = $transaksi->notaInvoice($customer->id, $bulan, $tahun);
        $dataTahun = $transaksi->dataTahun();
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
            'bulan' => $bulan,
            'tahun' => $tahun,
            'dataTahun' => $dataTahun,
            'stringBulan' => $stringBulan,
        ]);
    }

    public function cutoff(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'selectedData' => 'required',
            'total_ppn' => 'required',
            'bulan' => 'required',
            'tahun' => 'required',
        ]);
        $selectedData = array_filter(explode(',', $data['selectedData']));

        $db = new InvoicePpn;

        $d['tanggal'] = date('Y-m-d');
        $d['customer_id'] = $customer->id;
        $d['total_ppn'] = $data['total_ppn'];
        $d['no_invoice'] = $db->noInvoice();

        $k['uraian'] = 'PPN '.$data['bulan'].' '. $customer->singkatan;
        $k['nominal_transaksi'] = $d['total_ppn'];

        $kasBesar = new KasBesar;

        DB::beginTransaction();

        $invoice = $db->create($d);

        $k['invoice_ppn_id'] = $invoice->id;

        $store = $kasBesar->insertTagihan($k);

        $transaksi = Transaksi::whereIn('id', $selectedData)->update(['ppn' => 1]);

        foreach ($selectedData as $k => $v) {
            $detail['invoice_ppn_id'] = $invoice->id;
            $detail['transaksi_id'] = $v;
            InvoicePpnDetail::create($detail);
        }

        $group = GroupWa::where('untuk', 'kas-besar')->first();

        $pesan =    "🔵🔵🔵🔵🔵🔵🔵🔵🔵\n".
                    "*Form PPn Customer*\n".
                    "🔵🔵🔵🔵🔵🔵🔵🔵🔵\n\n".
                    "Uraian :  PPn ".$data['bulan'].' '. $data['tahun']."\n\n".
                    "Customer : ".$invoice->customer->nama."\n\n".
                    "Nilai :  *Rp. ".number_format($store->nominal_transaksi, 0, ',', '.')."*\n\n".
                    "Ditransfer ke rek:\n\n".
                    "Bank      : ".$store->bank."\n".
                    "Nama    : ".$store->nama_rek."\n".
                    "No. Rek : ".$store->no_rek."\n\n".
                    "==========================\n".
                    "Sisa Saldo Kas Besar : \n".
                    "Rp. ".number_format($store->saldo, 0, ',', '.')."\n\n".
                    "Total Modal Investor : \n".
                    "Rp. ".number_format($store->modal_investor_terakhir, 0, ',', '.')."\n\n".
                    "Terima kasih 🙏🙏🙏\n";

        $send = new StarSender($group->nama_group, $pesan);
        $res = $send->sendGroup();

        DB::commit();

        return redirect()->route('billing')->with('success', 'Berhasil menyimpan data ppn.');
    }
}