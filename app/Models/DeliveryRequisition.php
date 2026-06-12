<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryRequisition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_dr', 'store_requisition_id', 'tanggal', 'dibuat_oleh',
        'approved_by', 'approved_at', 'status', 'catatan', 'alasan_penolakan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'approved_at' => 'datetime',
    ];

    public function storeRequisition()
    {
        return $this->belongsTo(StoreRequisition::class);
    }

    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function details()
    {
        return $this->hasMany(DeliveryRequisitionDetail::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'draft' => '<span class="badge bg-secondary"><i class="bi bi-pencil-square me-1"></i>Draft</span>',
            'menunggu_approval' => '<span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Menunggu Approval Head Inventori</span>',
            'approved' => '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Disetujui Head Inventori</span>',
            'dikirim' => '<span class="badge badge-shipped"><i class="bi bi-truck me-1"></i>Sedang Dikirim</span>',
            'ditolak' => '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Ditolak Head Inventori</span>',
            'revisi' => '<span class="badge bg-info"><i class="bi bi-arrow-repeat me-1"></i>Perlu Revisi</span>',
            'selesai' => '<span class="badge bg-primary"><i class="bi bi-flag-fill me-1"></i>Selesai</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }
}
