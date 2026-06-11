<?php

namespace App\Exports;

use App\Models\PurchaseOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanPOExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(protected array $filters = []) {}

    public function collection()
    {
        $query = PurchaseOrder::with(['supplier', 'dibuatOleh']);
        if (!empty($this->filters['status'])) $query->where('status', $this->filters['status']);
        return $query->latest()->get();
    }

    public function headings(): array
    {
        return ['No', 'Nomor PO', 'Tanggal', 'Supplier', 'Total', 'Status'];
    }

    public function map($po): array
    {
        static $i = 0;
        $i++;
        return [$i, $po->nomor_po, $po->tanggal->format('d/m/Y'), $po->supplier?->nama_supplier, 'Rp ' . number_format($po->total, 0, ',', '.'), $po->status];
    }
}
