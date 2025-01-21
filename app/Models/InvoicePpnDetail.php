<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePpnDetail extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function invoicePpn()
    {
        return $this->belongsTo(InvoicePpn::class);
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
