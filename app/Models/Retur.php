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
            'draft' => '<span class="badge bg-secondary">Draft</span>',
            'menunggu_approval' => '<span class="badge bg-warning text-dark">Menunggu Approval</span>',
            'approved' => '<span class="badge bg-success">Approved</span>',
            'dikirim' => '<span class="badge bg-info">Dikirim</span>',
            'ditolak' => '<span class="badge bg-danger">Ditolak</span>',
            'selesai' => '<span class="badge bg-success">Selesai</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }
}
