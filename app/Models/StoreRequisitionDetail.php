<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreRequisitionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_requisition_id', 'barang_id', 'aset_id', 'qty', 'keterangan',
    ];

    public function storeRequisition()
    {
        return $this->belongsTo(StoreRequisition::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }
}
