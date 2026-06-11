<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'kode_supplier' => 'SUP-001',
                'nama_supplier' => 'PT Maju Jaya',
                'alamat' => 'Jl. Industri No. 10, Jakarta Barat',
                'telepon' => '021-5551001',
                'email' => 'supplier@majujaya.com',
                'pic' => 'Budi Santoso',
                'rating' => 4.5,
                'status' => 'aktif',
            ],
            [
                'kode_supplier' => 'SUP-002',
                'nama_supplier' => 'CV Berkah Abadi',
                'alamat' => 'Jl. Raya Bogor No. 45, Depok',
                'telepon' => '021-5552002',
                'email' => 'info@berkababadi.com',
                'pic' => 'Siti Rahayu',
                'rating' => 3.8,
                'status' => 'aktif',
            ],
            [
                'kode_supplier' => 'SUP-003',
                'nama_supplier' => 'UD Sumber Makmur',
                'alamat' => 'Jl. Pasar Lama No. 5, Bandung',
                'telepon' => '022-5553003',
                'email' => 'order@sumbermakmur.id',
                'pic' => 'Ahmad Yusuf',
                'rating' => 4.2,
                'status' => 'aktif',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::firstOrCreate(['kode_supplier' => $supplier['kode_supplier']], $supplier);
        }

        $this->command->info('Suppliers seeded successfully.');
    }
}
