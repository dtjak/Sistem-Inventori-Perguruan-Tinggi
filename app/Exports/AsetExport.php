<?php

namespace App\Exports;

use App\Models\Aset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AsetExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Aset::orderBy('nama_aset')->get();
    }

    public function headings(): array
    {
        return ['No', 'Kode Aset', 'Nama Aset', 'Kategori', 'Lokasi', 'Kondisi', 'Tanggal Perolehan', 'Nilai Perolehan', 'Umur Manfaat (thn)'];
    }

    public function map($aset): array
    {
        static $i = 0;
        $i++;
        return [
            $i,
            $aset->kode_aset,
            $aset->nama_aset,
            $aset->kategori_aset,
            $aset->lokasi,
            $aset->kondisi,
            $aset->tanggal_perolehan?->format('d/m/Y'),
            number_format($aset->nilai_perolehan, 0, ',', '.'),
            $aset->umur_manfaat,
        ];
    }
}
