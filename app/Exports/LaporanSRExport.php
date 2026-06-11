<?php

namespace App\Exports;

use App\Models\StoreRequisition;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanSRExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(protected array $filters = []) {}

    public function collection()
    {
        $query = StoreRequisition::with(['pemohon']);
        if (!empty($this->filters['status'])) $query->where('status', $this->filters['status']);
        return $query->latest()->get();
    }

    public function headings(): array
    {
        return ['No', 'Nomor SR', 'Tanggal', 'Unit Peminjam', 'Pemohon', 'Status'];
    }

    public function map($sr): array
    {
        static $i = 0;
        $i++;
        return [$i, $sr->nomor_sr, $sr->tanggal->format('d/m/Y'), $sr->unit_peminjam, $sr->pemohon?->name, $sr->status];
    }
}
