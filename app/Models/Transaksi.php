<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function getTanggalAttribute($value)
    {
        return date('d-m-Y', strtotime($value));
    }

    public function getBeratAttribute($value)
    {
        return number_format($value, 0, ',', '.');
    }

    public function getHargaAttribute($value)
    {
        return number_format($value, 0, ',', '.');
    }

    public function getTotalAttribute($value)
    {
        return number_format($value, 0, ',', '.');
    }

    public function getProfitAttribute($value)
    {
        return number_format($value, 0, ',', '.');
    }

    public function getPPHAttribute($value)
    {
        return number_format($value, 0, ',', '.');
    }
}
