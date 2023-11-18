<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasBesar extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function lastKasBesar()
    {
        return $this->latest()->orderBy('id', 'desc')->first();
    }

    public function lastKasBesarByMonth($month, $year)
    {
        return $this->whereMonth('tanggal', $month)->whereYear('tanggal', $year)->latest()->orderBy('id', 'desc')->first();
    }

    public function kasBesarNow($month, $year)
    {
        return $this->whereMonth('tanggal', $month)->whereYear('tanggal', $year)->get();
    }

    public function dataTahun()
    {
        return $this->selectRaw('YEAR(tanggal) tahun')->groupBy('tahun')->get();
    }

    public function nomorDeposit()
    {
        $lastNomor = $this->whereNotNull('nomor_deposit')->latest()->orderBy('id', 'desc')->first();

        if ($lastNomor) {
            return $lastNomor->nomor_deposit + 1;
        } else {
            return 1;
        }
    }

    // getNomorDepositAttribute
    public function getNomorDepositAttribute($value)
    {
        return str_pad($value, 2, '0', STR_PAD_LEFT);
    }


}
