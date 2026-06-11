<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpnameDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_opname_id', 'barang_id', 'stok_sistem', 'stok_fisik', 'keterangan',
    ];

    public function stockOpname()
    {
        return $this->belongsTo(StockOpname::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function getSelisihAttribute(): int
    {
        return $this->stok_fisik - $this->stok_sistem;
    }
}
