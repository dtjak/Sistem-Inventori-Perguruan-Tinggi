<?php

namespace App\Repositories;

use App\Models\Barang;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BarangRepository
{
    public function all(): Collection
    {
        return Barang::orderBy('nama_barang')->get();
    }

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = Barang::query();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('nama_barang', 'like', "%{$filters['search']}%")
                  ->orWhere('kode_barang', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['kategori'])) {
            $query->where('kategori', $filters['kategori']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('nama_barang')->paginate($perPage)->withQueryString();
    }

    public function find(int $id): Barang
    {
        return Barang::findOrFail($id);
    }

    public function create(array $data): Barang
    {
        return Barang::create($data);
    }

    public function update(Barang $barang, array $data): Barang
    {
        $barang->update($data);
        return $barang->fresh();
    }

    public function delete(Barang $barang): void
    {
        $barang->delete();
    }

    public function getKategori(): array
    {
        return Barang::distinct()->pluck('kategori')->toArray();
    }

    public function stokMenipis(): Collection
    {
        return Barang::whereColumn('stok_saat_ini', '<=', 'stok_minimum')
            ->where('status', 'aktif')
            ->orderBy('stok_saat_ini')
            ->get();
    }
}
