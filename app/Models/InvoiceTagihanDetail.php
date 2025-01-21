<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceTagihanDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function invoiceTagihan()
    {
        return $this->belongsTo(InvoiceTagihan::class);
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
