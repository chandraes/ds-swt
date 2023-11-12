<?php

namespace App\Http\Controllers;

use App\Models\Investor;
use Illuminate\Http\Request;

class InvestorController extends Controller
{
    public function index()
    {
        $data = Investor::all();
        return view('db.investor.index', [
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required',
            'no_wa' => 'required',
            'persentase' => 'required|integer',
            'bank' => 'required',
            'no_rek' => 'required',
            'nama_rek' => 'required',
        ]);

        $check = Investor::sum('persentase') + $data['persentase'];

        if ($check > 100) {
            return redirect()->back()->with('error', 'Persentase investor melebihi 100%');
        }

        Investor::create($data);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan!');
    }

    public function update(Request $request, Investor $investor)
    {
        $data = $request->validate([
            'nama' => 'required',
            'no_wa' => 'required',
            'persentase' => 'required|integer',
            'bank' => 'required',
            'no_rek' => 'required',
            'nama_rek' => 'required',
        ]);

        $check = Investor::whereNot('id', $investor->id)->sum('persentase') + $data['persentase'];

        if ($check > 100) {
            return redirect()->back()->with('error', 'Persentase investor melebihi 100%');
        }

        $investor->update($data);

        return redirect()->back()->with('success', 'Data berhasil diubah!');
    }

    public function destroy(Investor $investor)
    {
        $investor->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }
}
