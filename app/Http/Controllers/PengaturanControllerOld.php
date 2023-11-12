<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $data = User::all();
        
        return view('pengaturan.pengguna.index', [
            'data' => $data
        ]);
    }

    public function create()
    {
        return view('pengaturan.pengguna.create');
    }

    
    
}
