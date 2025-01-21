<?php

namespace App\Models;

use App\Models\Pajak\PpnKeluaran;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InvoiceTagihan extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function invoiceTagihanDetail()
    {
        return $this->hasMany(InvoiceTagihanDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function transaksi()
    {
        return $this->hasManyThrough(
            Transaksi::class,
            InvoiceTagihanDetail::class,
            'invoice_tagihan_id',
            'id',
            'id',
            'transaksi_id'
        );
    }

    public function noInvoice()
    {
        return $this->max('no_invoice') + 1 ?? 1;
    }

    public function keranjang_cutoff($data, $customer_id)
    {

        $db = new Transaksi();
        $dbKas = new KasBesar();
        $kasSupplier = new KasSupplier();
        $d = $db->notaTagihanKeranjang($customer_id);
        // dd($d);
        $penyesuaian = str_replace('.', '', $data['penyesuaian']);

        $dataId = collect($d)->pluck('id')->toArray();
        $customer = Customer::find($customer_id);

        $total_tagihan = $customer->ppn_kumulatif == 1 ? $d->sum('total_tagihan')+$penyesuaian : ($d->sum('total_tagihan') + $d->sum('total_ppn'))+$penyesuaian;
        $ppn = $customer->ppn_kumulatif == 0 ? $d->sum('total_ppn') : 0;

        $invoiceData['tanggal'] = date('Y-m-d');
        $invoiceData['customer_id'] = $customer->id;
        $invoiceData['total_tagihan'] = $total_tagihan;
        $invoiceData['no_invoice'] = $this->noInvoice();
        $invoiceData['ppn'] = $ppn;
        $invoiceData['penyesuaian'] = $penyesuaian;

        $kasData['uraian'] = 'Tagihan '. $customer->singkatan;
        $kasData['nominal_transaksi'] = $invoiceData['total_tagihan'];
        $kasData['nomor_tagihan'] = $invoiceData['no_invoice'];

        try {
            DB::beginTransaction();

            $invoice = $this->create($invoiceData);

            foreach($d as $item) {
                InvoiceTagihanDetail::create([
                    'transaksi_id' => $item->id,
                    'invoice_tagihan_id' => $invoice->id
                ]);
            }

            $kasData['invoice_tagihan_id'] = $invoice->id;

            $store = $dbKas->insertTagihan($kasData);

            $db->whereIn('id', $dataId)->update(['tagihan' => 1]);

            if ($customer->ppn_kumulatif == 0) {
                PpnKeluaran::create([
                    'invoice_tagihan_id' => $invoice->id,
                    'uraian' => 'PPN Tagihan '. $customer->singkatan. ' Invoice '.$invoice->no_invoice,
                    'nominal' => $ppn,
                    'saldo' => 0,
                ]);

                $db->whereIn('id', $dataId)->update(['ppn' => 1]);
            }

            DB::commit();

            $pesan2 = "";
            $results = $db->totalTagihan();

            foreach ($results as $result) {
                $total_tagihan = number_format($result->total_tagihan, 0, ',', '.');
                $pesan2 .= "Customer : {$result->customer->singkatan}\nTotal Tagihan: Rp. {$total_tagihan}\n\n";
            }

            $dbWa = new GroupWa;

            $group = $dbWa->where('untuk', 'kas-besar')->first();

            $last = $dbKas->lastKasBesar()->saldo ?? 0;
            $modalInvestor = ($dbKas->lastKasBesar()->modal_investor_terakhir ?? 0) * -1;
            $totalTagihan = $db->totalTagihan()->sum('total_tagihan');
            $totalTitipan = $kasSupplier->saldoTitipan() ?? 0;

            $ppn = new PpnKeluaran();

            $totalPpn = $ppn->where('is_finish', 0)->sum('nominal');

            $total_profit_bulan = ($totalTitipan+$totalTagihan+$last)-($modalInvestor+$totalPpn);

            $pesan =    "🔵🔵🔵🔵🔵🔵🔵🔵🔵\n".
                        "*PEMBAYARAN TAGIHAN*\n".
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
                        "Total Profit Saat Ini :" ."\n".
                        "Rp. ".number_format($total_profit_bulan, 0,',','.')."\n\n".
                        "Total PPN Belum Disetor : \n".
                        "Rp. ".number_format($totalPpn, 0, ',', '.')."\n\n".
                        "Total Modal Investor : \n".
                        "Rp. ".number_format($store->modal_investor_terakhir, 0, ',', '.')."\n\n".
                        "Terima kasih 🙏🙏🙏\n";

            $send = $dbWa->sendWa($group->nama_group, $pesan);


        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            return [
                'status' => 'error',
                'message' => 'Terjadi Kesalahan saat menyimpan data! '. $th->getMessage()
            ];
        }

        return [
            'status' => 'success',
            'message' => 'Berhasil menyimpan data!'
        ];
    }
}
