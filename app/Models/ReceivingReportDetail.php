<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivingReportDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiving_report_id', 'barang_id', 'qty_dipesan', 'qty_diterima', 'kondisi', 'keterangan',
    ];

    public function receivingReport()
    {
        return $this->belongsTo(ReceivingReport::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
