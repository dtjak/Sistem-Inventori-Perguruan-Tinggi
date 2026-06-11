<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin Staff Inventori',
                'nip' => '198001010001',
                'unit' => 'Inventori',
                'email' => 'staff.inventori@inventori.ac.id',
                'password' => Hash::make('password'),
                'status' => 'aktif',
                'role' => 'staff_inventori',
            ],
            [
                'name' => 'Head Inventori',
                'nip' => '197001010001',
                'unit' => 'Inventori',
                'email' => 'head.inventori@inventori.ac.id',
                'password' => Hash::make('password'),
                'status' => 'aktif',
                'role' => 'head_inventori',
            ],
            [
                'name' => 'Staff Unit Peminjam',
                'nip' => '199001010001',
                'unit' => 'Teknik Informatika',
                'email' => 'staff.unit@inventori.ac.id',
                'password' => Hash::make('password'),
                'status' => 'aktif',
                'role' => 'staff_unit',
            ],
            [
                'name' => 'Head Unit Peminjam',
                'nip' => '198501010001',
                'unit' => 'Teknik Informatika',
                'email' => 'head.unit@inventori.ac.id',
                'password' => Hash::make('password'),
                'status' => 'aktif',
                'role' => 'head_unit',
            ],
            [
                'name' => 'Staff Purchasing',
                'nip' => '199201010001',
                'unit' => 'Purchasing',
                'email' => 'staff.purchasing@inventori.ac.id',
                'password' => Hash::make('password'),
                'status' => 'aktif',
                'role' => 'staff_purchasing',
            ],
            [
                'name' => 'Head Purchasing',
                'nip' => '198201010001',
                'unit' => 'Purchasing',
                'email' => 'head.purchasing@inventori.ac.id',
                'password' => Hash::make('password'),
                'status' => 'aktif',
                'role' => 'head_purchasing',
            ],
            [
                'name' => 'Staff Finance',
                'nip' => '199501010001',
                'unit' => 'Finance',
                'email' => 'finance@inventori.ac.id',
                'password' => Hash::make('password'),
                'status' => 'aktif',
                'role' => 'finance',
            ],
            [
                'name' => 'PT Maju Jaya Supplier',
                'nip' => null,
                'unit' => null,
                'email' => 'supplier@majujaya.com',
                'password' => Hash::make('password'),
                'status' => 'aktif',
                'role' => 'supplier',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            $user->syncRoles([$role]);
        }

        $this->command->info('Users seeded successfully.');
    }
}
