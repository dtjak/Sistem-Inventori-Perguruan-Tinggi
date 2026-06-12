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
            'draft' => '<span class="badge bg-secondary"><i class="bi bi-pencil-square me-1"></i>Draft</span>',
            'menunggu_head_purchasing' => '<span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Menunggu Approval Head Purchasing</span>',
            'menunggu_finance' => '<span class="badge badge-finance"><i class="bi bi-cash-coin me-1"></i>Menunggu Verifikasi Finance</span>',
            'approved' => '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Disetujui (Siap Kirim)</span>',
            'dikirim' => '<span class="badge badge-shipped"><i class="bi bi-truck me-1"></i>Sedang Dikirim</span>',
            'selesai' => '<span class="badge badge-done"><i class="bi bi-flag-fill me-1"></i>Selesai</span>',
            'ditolak' => '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Ditolak</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }
}
