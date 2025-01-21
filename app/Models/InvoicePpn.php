<?php

namespace App\Models;

use App\Models\Pajak\PpnKeluaran;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InvoicePpn extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function invoicePpnDetail()
    {
        return $this->hasMany(InvoicePpnDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getIdTanggalAttribute()
    {
        return date('d-m-Y', strtotime($this->tanggal));
    }

    public function noInvoice()
    {
        return $this->max('no_invoice') + 1 ?? 1;
    }

    public function transaksi()
    {
        return $this->hasManyThrough(
            Transaksi::class,
            InvoicePpnDetail::class,
            'invoice_ppn_id',
            'id',
            'id',
            'transaksi_id'
        );
    }

    public function keranjang_cutoff($data, $customer_id)
    {
        $penyesuaian = str_replace('.', '', $data['penyesuaian']);
        $customer = Customer::find($customer_id);
        $db = new Transaksi();
        $dbKasBesar = new KasBesar;
        $dbKasSupplier = new KasSupplier;

        $d = $db->invoicePpnKeranjang($customer_id);
        $dataId = collect($d)->pluck('id')->toArray();

        $total_ppn = $d->sum('total_ppn') + $penyesuaian;

        $invoiceData['tanggal'] = date('Y-m-d');
        $invoiceData['customer_id'] = $customer->id;
        $invoiceData['total_ppn'] = $total_ppn;
        $invoiceData['no_invoice'] = $this->noInvoice();
        $invoiceData['bayar'] = 1;

        $data['bulan'] = Carbon::parse($invoiceData['tanggal'])->locale('id')->monthName;
        $data['tahun'] = Carbon::parse($invoiceData['tanggal'])->year;

        $kasData['uraian'] = 'PPN '.$data['bulan'].' '. $customer->singkatan;
        $kasData['nominal_transaksi'] = $total_ppn;

        try {
            DB::beginTransaction();

            $invoice = $this->create($invoiceData);
            $kasData['invoice_ppn_id'] = $invoice->id;

            $store = $dbKasBesar->insertTagihan($kasData);

            $transaksi = $db->whereIn('id', $dataId)->update(['ppn' => 1]);

            foreach ($dataId as $k => $v) {
                $detail['invoice_ppn_id'] = $invoice->id;
                $detail['transaksi_id'] = $v;
                InvoicePpnDetail::create($detail);
            }

            PpnKeluaran::create([
                'invoice_ppn_id' => $invoice->id,
                'uraian' => $store->uraian,
                'nominal' => $total_ppn,
                'saldo' => 0,
            ]);

            DB::commit();

            $ppn = new PpnKeluaran();

            $totalPpn = $ppn->where('is_finish', 0)->sum('nominal');

            $last = $dbKasBesar->lastKasBesar()->saldo ?? 0;
            $modalInvestor = ($dbKasBesar->lastKasBesar()->modal_investor_terakhir ?? 0) * -1;
            $totalTagihan = $db->totalTagihan()->sum('total_tagihan');
            $totalTitipan = $dbKasSupplier->saldoTitipan() ?? 0;

            $total_profit_bulan = ($totalTitipan+$totalTagihan+$last)-($modalInvestor+$totalPpn);

            $dbWa = new GroupWa;

            $group = $dbWa->where('untuk', 'kas-besar')->first();

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
                'message' => 'Gagal menyimpan data!. '.$th->getMessage(),
            ];
        }

        return [
            'status' => 'success',
            'message' => 'Berhasil menyimpan data',
        ];

    }
}
