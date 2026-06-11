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
            'draft' => '<span class="badge bg-secondary">Draft</span>',
            'menunggu_approval' => '<span class="badge bg-warning text-dark">Menunggu Approval</span>',
            'approved' => '<span class="badge bg-success">Approved</span>',
            'ditolak' => '<span class="badge bg-danger">Ditolak</span>',
            'revisi' => '<span class="badge bg-info">Revisi</span>',
            'selesai' => '<span class="badge bg-primary">Selesai</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }
}
