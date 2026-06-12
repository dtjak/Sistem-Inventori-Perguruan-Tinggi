<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Retur extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_retur', 'receiving_report_id', 'purchase_order_id', 'supplier_id', 'tanggal',
        'alasan', 'dibuat_oleh', 'approved_by', 'approved_at',
        'status', 'catatan', 'alasan_penolakan',
        'nomor_resi', 'kurir_ekspedisi', 'tanggal_pengiriman', 'catatan_pengiriman',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'approved_at' => 'datetime',
        'tanggal_pengiriman' => 'datetime',
    ];

    public function receivingReport()
    {
        return $this->belongsTo(ReceivingReport::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
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
        return $this->hasMany(ReturDetail::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'draft' => '<span class="badge bg-secondary"><i class="bi bi-pencil-square me-1"></i>Draft</span>',
            'menunggu_approval' => '<span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Menunggu Approval Head Inventori</span>',
            'approved' => '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Disetujui (Menunggu Pengiriman)</span>',
            'dikirim' => '<span class="badge badge-shipped"><i class="bi bi-truck me-1"></i>Barang Pengganti Dikirim</span>',
            'ditolak' => '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Ditolak Head Inventori</span>',
            'selesai' => '<span class="badge badge-done"><i class="bi bi-flag-fill me-1"></i>Selesai</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }
}
