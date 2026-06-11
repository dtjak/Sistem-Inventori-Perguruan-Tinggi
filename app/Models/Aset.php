<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_aset',
        'nama_aset',
        'kategori_aset',
        'lokasi',
        'kondisi',
        'tanggal_perolehan',
        'nilai_perolehan',
        'umur_manfaat',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_perolehan' => 'date',
        'nilai_perolehan' => 'decimal:2',
        'umur_manfaat' => 'integer',
    ];

    public function getKondisiBadgeAttribute(): string
    {
        return match ($this->kondisi) {
            'baik' => '<span class="badge bg-success">Baik</span>',
            'rusak_ringan' => '<span class="badge bg-warning">Rusak Ringan</span>',
            'rusak_berat' => '<span class="badge bg-danger">Rusak Berat</span>',
            'tidak_layak' => '<span class="badge bg-secondary">Tidak Layak</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }
}
