<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_po', 'purchase_requisition_id', 'supplier_id', 'tanggal', 'tanggal_kirim',
        'total', 'dibuat_oleh', 'approved_head_purchasing', 'approved_head_at',
        'approved_finance', 'approved_finance_at', 'status', 'catatan', 'alasan_penolakan',
        'nomor_resi', 'kurir_ekspedisi', 'tanggal_pengiriman', 'catatan_pengiriman',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_kirim' => 'date',
        'approved_head_at' => 'datetime',
        'approved_finance_at' => 'datetime',
        'tanggal_pengiriman' => 'datetime',
        'total' => 'decimal:2',
    ];

    public function purchaseRequisition()
    {
        return $this->belongsTo(PurchaseRequisition::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function approvedHeadPurchasing()
    {
        return $this->belongsTo(User::class, 'approved_head_purchasing');
    }

    public function approvedFinance()
    {
        return $this->belongsTo(User::class, 'approved_finance');
    }

    public function details()
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }

    public function receivingReports()
    {
        return $this->hasMany(ReceivingReport::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'draft' => '<span class="badge bg-secondary">Draft</span>',
            'menunggu_head_purchasing' => '<span class="badge bg-warning text-dark">Menunggu Head Purchasing</span>',
            'menunggu_finance' => '<span class="badge bg-info">Menunggu Finance</span>',
            'approved' => '<span class="badge bg-success">Approved</span>',
            'dikirim' => '<span class="badge bg-primary">Dikirim</span>',
            'selesai' => '<span class="badge bg-success">Selesai</span>',
            'ditolak' => '<span class="badge bg-danger">Ditolak</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }
}
