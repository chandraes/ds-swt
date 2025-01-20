<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $data = Customer::all();
        return view('db.customer.index', [
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required',
            'npwp' => 'required',
            'singkatan' => 'required',
            'cp' => 'required',
            'no_wa' => 'required',
            'alamat' => 'required',
            'harga' => 'required',
            'ppn_kumulatif' => 'required',
        ]);

        $data['harga'] = str_replace('.', '', $data['harga']);

        Customer::create($data);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan!');

    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'nama' => 'required',
            'npwp' => 'required',
            'singkatan' => 'required',
            'cp' => 'required',
            'no_wa' => 'required',
            'alamat' => 'required',
            'harga' => 'required',
            'ppn_kumulatif' => 'required',
        ]);

        $data['harga'] = str_replace('.', '', $data['harga']);

        $customer->update($data);

        return redirect()->back()->with('success', 'Data berhasil diubah!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }

    public function update_harga(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'harga' => 'required',
        ]);

        $data['harga'] = str_replace('.', '', $data['harga']);

        $customer->update($data);

        return redirect()->back()->with('success', 'Data berhasil diubah!');
    }
}
