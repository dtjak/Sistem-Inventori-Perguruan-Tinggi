<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreRequisition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_sr', 'tanggal', 'unit_peminjam', 'pemohon_id',
        'approved_by', 'approved_at', 'status', 'catatan', 'alasan_penolakan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'approved_at' => 'datetime',
    ];

    public function pemohon()
    {
        return $this->belongsTo(User::class, 'pemohon_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function details()
    {
        return $this->hasMany(StoreRequisitionDetail::class);
    }

    public function deliveryRequisitions()
    {
        return $this->hasMany(DeliveryRequisition::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'draft' => '<span class="badge bg-secondary"><i class="bi bi-pencil-square me-1"></i>Draft</span>',
            'menunggu_approval' => '<span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Menunggu Approval Head Unit</span>',
            'disetujui' => '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Disetujui Head Unit</span>',
            'ditolak' => '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Ditolak Head Unit</span>',
            'revisi' => '<span class="badge bg-info"><i class="bi bi-arrow-repeat me-1"></i>Perlu Revisi</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }
}
