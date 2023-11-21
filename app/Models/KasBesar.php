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

    public function nomorTitipan()
    {
        $lastNomor = $this->whereNotNull('nomor_titipan')->latest()->orderBy('id', 'desc')->first();

        if ($lastNomor) {
            return $lastNomor->nomor_titipan + 1;
        } else {
            return 1;
        }
    }

    public function getNomorTitipanAttribute($value)
    {
        return str_pad($value, 2, '0', STR_PAD_LEFT);
    }

    // getNomorDepositAttribute
    public function getNomorDepositAttribute($value)
    {
        return str_pad($value, 2, '0', STR_PAD_LEFT);
    }


}
