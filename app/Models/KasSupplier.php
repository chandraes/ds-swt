<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasSupplier extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function lastKasSupplier($supplier_id)
    {
        return $this->where('supplier_id', $supplier_id)->latest()->orderBy('id', 'desc')->first();
    }
}
