<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;

class FormTransaksiController extends Controller
{
    public function tambah(Customer $customer)
    {

        $data = Transaksi::where('customer_id', $customer->id)->where('status', 0)->get();
        $supplier = Supplier::all();

        return view('billing.form-transaksi.index', [
            'data' => $data,
            'customer' => $customer,
            'supplier' => $supplier,
        ]);
    }

    public function tambah_store(Request $request)
    {
        $data = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'supplier_id' => 'required|exists:suppliers,id',
                'tanggal' => 'required',
                'nota_timbangan' => 'required|unique:transaksis,nota_timbangan|min:9|max:9',
                'berat' => 'required',
            ]);

        $tgl = $data['tanggal'];
        $customer = Customer::findOrFail($data['customer_id']);
        $data['berat'] = str_replace('.', '', $data['berat']);
        $data['tanggal'] = date('Y-m-d', strtotime($data['tanggal']));
        $data['harga'] = str_replace('.', '', $customer->harga);
        $data['total'] = $data['berat'] * $data['harga'];
        $data['pph'] = $data['total'] * 0.0025;
        $data['profit'] = $data['total'] * 0.01;

        $store = Transaksi::create($data);

        return redirect()->route('form-transaksi.tambah', ['customer' => $customer->id])->with(['success' => 'Data Berhasil Ditambahkan', 'tgl' => $tgl, 'supplier' => $data['supplier_id']]);

    }

    public function delete(Transaksi $transaksi)
    {
        $transaksi->delete();

        return redirect()->back()->with('success', 'Data Berhasil Dihapus');
    }

    public function lanjutkan(Customer $customer)
    {
        // update status to 1 for all transaksi with customer_id = $customer->id and status = 0
        $data = Transaksi::where('customer_id', $customer->id)->where('status', 0)->update(['status' => 1]);

        return redirect()->route('billing')->with('success', 'Data Berhasil Disimpan');
    }
}
