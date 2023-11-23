<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceTagihan extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function invoiceTagihanDetail()
    {
        return $this->hasMany(InvoiceTagihanDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function transaksi()
    {
        return $this->hasManyThrouhg(InvoiceTagihanDetail::class, Transaksi::class);
    }
}
