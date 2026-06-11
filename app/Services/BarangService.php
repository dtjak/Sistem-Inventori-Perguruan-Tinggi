<?php

namespace App\Services;

use App\Models\Barang;
use App\Repositories\BarangRepository;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BarangImport;

class BarangService
{
    public function __construct(
        protected BarangRepository $repository
    ) {}

    public function generateKode(): string
    {
        $last = Barang::withTrashed()->orderBy('id', 'desc')->first();
        $num = $last ? ((int) substr($last->kode_barang, 4)) + 1 : 1;
        return 'BRG-' . str_pad($num, 3, '0', STR_PAD_LEFT);
    }

    public function create(array $data): Barang
    {
        $data['kode_barang'] = $this->generateKode();
        return $this->repository->create($data);
    }

    public function update(Barang $barang, array $data): Barang
    {
        return $this->repository->update($barang, $data);
    }

    public function delete(Barang $barang): void
    {
        $this->repository->delete($barang);
    }

    public function import(UploadedFile $file): void
    {
        Excel::import(new BarangImport, $file);
    }

    public function kurangiStok(int $barangId, int $qty): void
    {
        $barang = Barang::findOrFail($barangId);
        $barang->decrement('stok_saat_ini', $qty);
    }

    public function tambahStok(int $barangId, int $qty): void
    {
        $barang = Barang::findOrFail($barangId);
        $barang->increment('stok_saat_ini', $qty);
    }
}
