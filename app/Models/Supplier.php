<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_supplier',
        'nama_supplier',
        'alamat',
        'telepon',
        'email',
        'pic',
        'rating',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
    ];

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function returs()
    {
        return $this->hasMany(Retur::class);
    }

    public function getRatingBadgeAttribute(): string
    {
        $rating = (float) $this->rating;
        $class = match (true) {
            $rating >= 4.0 => 'success',
            $rating >= 3.0 => 'warning',
            $rating >= 2.0 => 'danger',
            default => 'secondary',
        };
        return '<span class="badge bg-' . $class . '">' . number_format($rating, 1) . ' ★</span>';
    }
}
