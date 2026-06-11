<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $barangs = [
            ['kode_barang' => 'BRG-001', 'nama_barang' => 'Kertas A4 80gsm', 'kategori' => 'ATK', 'satuan' => 'Rim', 'stok_minimum' => 10, 'stok_saat_ini' => 50, 'lokasi_gudang' => 'Rak A-1', 'status' => 'aktif'],
            ['kode_barang' => 'BRG-002', 'nama_barang' => 'Tinta Printer Hitam', 'kategori' => 'ATK', 'satuan' => 'Botol', 'stok_minimum' => 5, 'stok_saat_ini' => 20, 'lokasi_gudang' => 'Rak A-2', 'status' => 'aktif'],
            ['kode_barang' => 'BRG-003', 'nama_barang' => 'Ballpoint Hitam', 'kategori' => 'ATK', 'satuan' => 'Lusin', 'stok_minimum' => 5, 'stok_saat_ini' => 15, 'lokasi_gudang' => 'Rak A-3', 'status' => 'aktif'],
            ['kode_barang' => 'BRG-004', 'nama_barang' => 'Toner Printer LaserJet', 'kategori' => 'Elektronik', 'satuan' => 'Pcs', 'stok_minimum' => 3, 'stok_saat_ini' => 8, 'lokasi_gudang' => 'Rak B-1', 'status' => 'aktif'],
            ['kode_barang' => 'BRG-005', 'nama_barang' => 'Flashdisk 32GB', 'kategori' => 'Elektronik', 'satuan' => 'Pcs', 'stok_minimum' => 5, 'stok_saat_ini' => 12, 'lokasi_gudang' => 'Rak B-2', 'status' => 'aktif'],
            ['kode_barang' => 'BRG-006', 'nama_barang' => 'Mouse Wireless', 'kategori' => 'Elektronik', 'satuan' => 'Pcs', 'stok_minimum' => 3, 'stok_saat_ini' => 7, 'lokasi_gudang' => 'Rak B-3', 'status' => 'aktif'],
            ['kode_barang' => 'BRG-007', 'nama_barang' => 'Keyboard USB', 'kategori' => 'Elektronik', 'satuan' => 'Pcs', 'stok_minimum' => 3, 'stok_saat_ini' => 5, 'lokasi_gudang' => 'Rak B-4', 'status' => 'aktif'],
            ['kode_barang' => 'BRG-008', 'nama_barang' => 'Spidol Whiteboard', 'kategori' => 'ATK', 'satuan' => 'Set', 'stok_minimum' => 5, 'stok_saat_ini' => 18, 'lokasi_gudang' => 'Rak A-4', 'status' => 'aktif'],
            ['kode_barang' => 'BRG-009', 'nama_barang' => 'Map Plastik', 'kategori' => 'ATK', 'satuan' => 'Pcs', 'stok_minimum' => 20, 'stok_saat_ini' => 3, 'lokasi_gudang' => 'Rak A-5', 'status' => 'aktif'],
            ['kode_barang' => 'BRG-010', 'nama_barang' => 'Amplop Coklat', 'kategori' => 'ATK', 'satuan' => 'Pack', 'stok_minimum' => 5, 'stok_saat_ini' => 25, 'lokasi_gudang' => 'Rak A-6', 'status' => 'aktif'],
        ];

        foreach ($barangs as $barang) {
            Barang::firstOrCreate(['kode_barang' => $barang['kode_barang']], $barang);
        }

        $this->command->info('Barang seeded successfully.');
    }
}
