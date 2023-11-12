<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\DB;
Use App\Http\Controllers\Hash;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $users = User::all();

        return view('pengaturan.pengguna.index', [
            'data' => $users
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pengaturan.pengguna.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => $data['username'],
                'password' => $data['password'],
            ]);
        });
        // return redirect()->back()->with('success', 'Data berhasil ditambahkan!');
        return redirect()->route('akun.index')->with('success', 'User Berhasil Ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('pengaturan.pengguna.edit',  compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $user = User::findOrFail($id);
        DB::transaction(function () use ($request, $data, $user, $id) {
            if ($data['password'] == '') {
                $this->validate($request, [
                    'name' => 'required|string|max:255',
                    'email' => 'nullable|string|email|max:255',
                    'username' => 'required|string|max:255',
                ]);
                $data['password'] = $user->password;
            } else {
                $this->validate($request, [
                    'name' => 'required|string|max:255',
                    'email' => 'nullable|string|email|max:255',
                    'username' => 'required|string|max:255',
                    'password' => 'required|string|min:6|confirmed',
                ]);
                $data['password'] = $data['password'];
            }
            $user->update($data);
        });
        //  return redirect()->back()->with('success', 'Data berhasil diubah!');
         return redirect()->route('akun.index')->with('success', 'User Berhasil Diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $check = User::count();

        if ($check == 1) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus user, karena hanya ada 1 user');
        }
        
        DB::transaction(function () use ($id) {
            $user = User::findOrFail($id);
            $user->delete();
        });
        // return redirect()->back()->with('success', 'Data berhasil diubah!');
        return redirect()->route('akun.index')->with('success', 'User has been deleted');
    }

}
