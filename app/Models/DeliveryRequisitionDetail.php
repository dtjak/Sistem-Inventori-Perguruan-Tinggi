<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryRequisitionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_requisition_id', 'barang_id', 'qty_distribusi', 'keterangan',
    ];

    public function deliveryRequisition()
    {
        return $this->belongsTo(DeliveryRequisition::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
