<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesanWa extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesan',
        'tujuan',
        'status',
    ];
}
