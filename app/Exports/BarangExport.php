<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BarangExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Barang::orderBy('nama_barang')->get();
    }

    public function headings(): array
    {
        return ['No', 'Kode Barang', 'Nama Barang', 'Kategori', 'Satuan', 'Stok Minimum', 'Stok Saat Ini', 'Lokasi Gudang', 'Status Stok', 'Status'];
    }

    public function map($barang): array
    {
        static $i = 0;
        $i++;
        return [
            $i,
            $barang->kode_barang,
            $barang->nama_barang,
            $barang->kategori,
            $barang->satuan,
            $barang->stok_minimum,
            $barang->stok_saat_ini,
            $barang->lokasi_gudang,
            $barang->status_stok,
            $barang->status,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4e73df']], 'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true]],
        ];
    }
}
