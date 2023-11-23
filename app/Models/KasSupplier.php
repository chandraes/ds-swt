<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasSupplier extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function getTanggalAttribute($value)
    {
        return date('d-m-Y', strtotime($value));
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function lastKasSupplier($supplier_id)
    {
        return $this->where('supplier_id', $supplier_id)->latest()->orderBy('id', 'desc')->first();
    }

    public function kasSupplierNow($supplier_id, $month, $year)
    {
        return $this->where('supplier_id', $supplier_id)->whereMonth('tanggal', $month)->whereYear('tanggal', $year)->get();
    }

    public function lastKasSupplierByMonth($supplier_id, $month, $year)
    {
        return $this->where('supplier_id', $supplier_id)->whereMonth('tanggal', $month)->whereYear('tanggal', $year)->latest()->orderBy('id', 'desc')->first();
    }

    public function dataTahun()
    {
        return $this->selectRaw('YEAR(tanggal) tahun')->groupBy('tahun')->get();
    }
}
