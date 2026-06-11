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
            'draft' => '<span class="badge bg-secondary">Draft</span>',
            'menunggu_approval' => '<span class="badge bg-warning text-dark">Menunggu Approval</span>',
            'disetujui' => '<span class="badge bg-success">Disetujui</span>',
            'ditolak' => '<span class="badge bg-danger">Ditolak</span>',
            'revisi' => '<span class="badge bg-info">Revisi</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }
}
