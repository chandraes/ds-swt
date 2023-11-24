<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Transaksi;
use App\Models\InvoiceTagihan;
use App\Models\InvoiceTagihanDetail;
use App\Models\KasBesar;
use App\Models\GroupWa;
use App\Services\StarSender;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class NotaTagihanController extends Controller
{
    public function index(Customer $customer)
    {
        $transaksi = new Transaksi;
        $totalBerat = $transaksi->notaTransaksiBerat($customer->id);
        $total = $transaksi->notaTransaksiTotal($customer->id);
        $totalTagihan = $transaksi->notaTransaksiTotalTagihan($customer->id);
        $totalProfit = $transaksi->notaTagihanTotalProfit($customer->id);
        $totalPPH = $transaksi->notaTagihanTotalPPH($customer->id);
        $data = $transaksi->notaTagihan($customer->id);
        $supplier = Supplier::select('id', 'nama', 'nickname')->get();

        return view('billing.nota-tagihan.index', [
            'data' => $data,
            'customer' => $customer,
            'totalBerat' => $totalBerat,
            'total' => $total,
            'totalTagihan' => $totalTagihan,
            'totalProfit' => $totalProfit,
            'totalPPH' => $totalPPH,
            'supplier' => $supplier,
        ]);
    }

    public function edit_store(Transaksi $transaksi, Request $request)
    {
        $data = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal' => 'required',
            'nota_timbangan' => 'required|min:9|max:9',
            'berat' => 'required',
        ]);

        $supplier = Supplier::findOrFail($data['supplier_id']);

        if ($supplier->persen_profit == null || $supplier->persen_profit == 0) {
            return redirect()->back()->with('error', 'Supplier belum memiliki persen profit! Harap hubungi admin untuk mengisi persen profit supplier!');
        }

        $persen_profit = $supplier->persen_profit / 100;

        $data['berat'] = str_replace('.', '', $data['berat']);
        $data['tanggal'] = date('Y-m-d', strtotime($data['tanggal']));
        $data['total'] = $data['berat'] * $transaksi->harga;
        $data['pph'] = $data['total'] * 0.0025;
        $data['profit'] = $data['total'] * $persen_profit;
        $data['total_ppn'] = $data['total'] * 0.11;
        $data['total_tagihan'] = $data['total'] - $data['pph'];
        $data['total_bayar'] = $data['total_tagihan'] - $data['profit'];

        try {

            $transaksi->update($data);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Terdapat Nota Timbang yang sama!');
        }

        return redirect()->back()->with('success', 'Data Berhasil Diubah');
    }

    public function cutoff(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'total_tagih' => 'required|integer',
            'selectedData' => 'required',
        ]);

        // convert selectedData to array and remove empty value
        $selectedData = array_filter(explode(',', $data['selectedData']));

        $db = new InvoiceTagihan;

        $d['tanggal'] = date('Y-m-d');
        $d['customer_id'] = $data['customer_id'];
        $d['total_tagihan'] = $data['total_tagih'];
        $d['no_invoice'] = $db->noInvoice();

        $k['uraian'] = 'Tagihan '. Customer::find($data['customer_id'])->singkatan;
        $k['nominal_transaksi'] = $d['total_tagihan'];
        $k['nomor_tagihan'] = $d['no_invoice'];

        $kasBesar = new KasBesar;

        $transaksi = new Transaksi;


        DB::beginTransaction();

        $invoice = $db->create($d);

        $k['invoice_tagihan_id'] = $invoice->id;
        $store = $kasBesar->insertTagihan($k);

        $transaksi = Transaksi::whereIn('id', $selectedData)->update(['tagihan' => 1]);

        $results = $transaksi->totalTagihan();

        $pesan2 = "Customer : ";

        foreach ($results as $result) {
            $total_tagihan = number_format($result->total_tagihan, 0, ',', '.');
            $total_profit = number_format($result->total_profit, 0, ',', '.');
            $pesan2 .= "{$result->customer->singkatan}\nTotal Tagihan: Rp. {$total_tagihan}\nTotal Profit: Rp.{$total_profit}\n\n";
        }

        foreach ($selectedData as $k => $v) {

            $detail['invoice_tagihan_id'] = $invoice->id;
            $detail['transaksi_id'] = $v;
            InvoiceTagihanDetail::create($detail);
        }

        $group = GroupWa::where('untuk', 'kas-besar')->first();

        $pesan =    "🔵🔵🔵🔵🔵🔵🔵🔵🔵\n".
                    "*Form Tagihan Customer*\n".
                    "🔵🔵🔵🔵🔵🔵🔵🔵🔵\n\n".
                    "*TC".$store->nomor_tagihan."*\n\n".
                    "Customer : ".$invoice->customer->nama."\n\n".
                    "Nilai :  *Rp. ".number_format($store->nominal_transaksi, 0, ',', '.')."*\n\n".
                    "Ditransfer ke rek:\n\n".
                    "Bank      : ".$store->bank."\n".
                    "Nama    : ".$store->nama_rek."\n".
                    "No. Rek : ".$store->no_rek."\n\n".
                    "==========================\n".
                    $pesan2.
                    "Sisa Saldo Kas Besar : \n".
                    "Rp. ".number_format($store->saldo, 0, ',', '.')."\n\n".
                    "Total Modal Investor : \n".
                    "Rp. ".number_format($store->modal_investor_terakhir, 0, ',', '.')."\n\n".
                    "Terima kasih 🙏🙏🙏\n";
        $send = new StarSender($group->nama_group, $pesan);
        $res = $send->sendGroup();

        DB::commit();

        return redirect()->route('billing')->with('success', 'Berhasil menyimpan data tagihan.');

    }
}
