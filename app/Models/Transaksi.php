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

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function notaTagihan($customer_id)
    {
        return $this->where('customer_id', $customer_id)->where('status', 1)->where('tagihan', 0)->get();
    }

}
