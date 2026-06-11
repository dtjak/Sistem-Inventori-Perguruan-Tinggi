<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'satuan',
        'stok_minimum',
        'stok_saat_ini',
        'lokasi_gudang',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'stok_minimum' => 'integer',
        'stok_saat_ini' => 'integer',
    ];

    public function storeRequisitionDetails()
    {
        return $this->hasMany(StoreRequisitionDetail::class);
    }

    public function deliveryRequisitionDetails()
    {
        return $this->hasMany(DeliveryRequisitionDetail::class);
    }

    public function purchaseRequisitionDetails()
    {
        return $this->hasMany(PurchaseRequisitionDetail::class);
    }

    public function purchaseOrderDetails()
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }

    public function receivingReportDetails()
    {
        return $this->hasMany(ReceivingReportDetail::class);
    }

    public function stockOpnameDetails()
    {
        return $this->hasMany(StockOpnameDetail::class);
    }

    public function isStokMenipis(): bool
    {
        return $this->stok_saat_ini <= $this->stok_minimum;
    }

    public function getStatusStokAttribute(): string
    {
        if ($this->stok_saat_ini === 0) return 'Habis';
        if ($this->stok_saat_ini <= $this->stok_minimum) return 'Menipis';
        return 'Tersedia';
    }
}
