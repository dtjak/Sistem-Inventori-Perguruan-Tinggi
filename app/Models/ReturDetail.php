<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'retur_id', 'barang_id', 'qty', 'keterangan',
    ];

    public function retur()
    {
        return $this->belongsTo(Retur::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
