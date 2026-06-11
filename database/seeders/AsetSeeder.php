<?php

namespace Database\Seeders;

use App\Models\Aset;
use Illuminate\Database\Seeder;

class AsetSeeder extends Seeder
{
    public function run(): void
    {
        $asets = [
            [
                'kode_aset' => 'AST-001',
                'nama_aset' => 'Laptop Dell Latitude 5420',
                'kategori_aset' => 'Komputer',
                'lokasi' => 'Ruang Lab TI-1',
                'kondisi' => 'baik',
                'tanggal_perolehan' => '2022-01-15',
                'nilai_perolehan' => 12500000,
                'umur_manfaat' => 5,
            ],
            [
                'kode_aset' => 'AST-002',
                'nama_aset' => 'Printer HP LaserJet Pro M404n',
                'kategori_aset' => 'Printer',
                'lokasi' => 'Ruang Administrasi',
                'kondisi' => 'baik',
                'tanggal_perolehan' => '2021-06-10',
                'nilai_perolehan' => 3200000,
                'umur_manfaat' => 5,
            ],
            [
                'kode_aset' => 'AST-003',
                'nama_aset' => 'Proyektor Epson EB-X41',
                'kategori_aset' => 'AV',
                'lokasi' => 'Ruang Rapat',
                'kondisi' => 'baik',
                'tanggal_perolehan' => '2020-09-05',
                'nilai_perolehan' => 5500000,
                'umur_manfaat' => 7,
            ],
        ];

        foreach ($asets as $aset) {
            Aset::firstOrCreate(['kode_aset' => $aset['kode_aset']], $aset);
        }

        $this->command->info('Aset seeded successfully.');
    }
}
