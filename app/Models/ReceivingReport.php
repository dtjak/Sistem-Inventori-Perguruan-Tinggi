<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceivingReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_rr', 'purchase_order_id', 'tanggal_terima', 'penerima_id',
        'approved_by', 'approved_at', 'status', 'catatan', 'alasan_penolakan',
    ];

    protected $casts = [
        'tanggal_terima' => 'date',
        'approved_at' => 'datetime',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function penerima()
    {
        return $this->belongsTo(User::class, 'penerima_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function details()
    {
        return $this->hasMany(ReceivingReportDetail::class);
    }

    public function returs()
    {
        return $this->hasMany(Retur::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'draft' => '<span class="badge bg-secondary"><i class="bi bi-pencil-square me-1"></i>Draft</span>',
            'menunggu_approval' => '<span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Menunggu Approval Head Inventori</span>',
            'approved' => '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Disetujui Head Inventori</span>',
            'ditolak' => '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Ditolak Head Inventori</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }
}
