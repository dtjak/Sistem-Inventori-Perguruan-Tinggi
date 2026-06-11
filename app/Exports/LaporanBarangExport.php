<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanBarangExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(protected array $filters = []) {}

    public function collection()
    {
        $query = Barang::query();
        if (!empty($this->filters['kategori'])) $query->where('kategori', $this->filters['kategori']);
        if (!empty($this->filters['status'])) $query->where('status', $this->filters['status']);
        return $query->orderBy('nama_barang')->get();
    }

    public function headings(): array
    {
        return ['No', 'Kode Barang', 'Nama Barang', 'Kategori', 'Satuan', 'Stok Min', 'Stok Saat Ini', 'Lokasi', 'Status Stok', 'Status'];
    }

    public function map($barang): array
    {
        static $i = 0;
        $i++;
        return [$i, $barang->kode_barang, $barang->nama_barang, $barang->kategori, $barang->satuan, $barang->stok_minimum, $barang->stok_saat_ini, $barang->lokasi_gudang, $barang->status_stok, $barang->status];
    }
}
