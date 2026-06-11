<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequisitionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_requisition_id', 'barang_id', 'qty', 'estimasi_harga', 'keterangan',
    ];

    protected $casts = [
        'estimasi_harga' => 'decimal:2',
    ];

    public function purchaseRequisition()
    {
        return $this->belongsTo(PurchaseRequisition::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function getSubtotalAttribute()
    {
        return $this->qty * $this->estimasi_harga;
    }
}
