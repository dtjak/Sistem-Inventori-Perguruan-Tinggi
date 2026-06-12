<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Master Data
            'barang.view', 'barang.create', 'barang.edit', 'barang.delete', 'barang.import', 'barang.export',
            'aset.view', 'aset.create', 'aset.edit', 'aset.delete', 'aset.export',
            'supplier.view', 'supplier.create', 'supplier.edit', 'supplier.delete',

            // Store Requisition
            'sr.view', 'sr.create', 'sr.edit', 'sr.delete', 'sr.approve', 'sr.reject',

            // Delivery Requisition
            'dr.view', 'dr.create', 'dr.edit', 'dr.delete', 'dr.approve', 'dr.reject',

            // Purchase Requisition
            'pr.view', 'pr.create', 'pr.edit', 'pr.delete', 'pr.approve', 'pr.reject',

            // Purchase Order
            'po.view', 'po.create', 'po.edit', 'po.delete',
            'po.approve_head', 'po.reject_head',
            'po.approve_finance', 'po.reject_finance',

            // Receiving Report
            'rr.view', 'rr.create', 'rr.edit', 'rr.delete', 'rr.approve', 'rr.reject',

            // Retur
            'retur.view', 'retur.create', 'retur.edit', 'retur.delete', 'retur.approve', 'retur.reject',

            // Stock Opname
            'opname.view', 'opname.create', 'opname.edit', 'opname.delete',

            // Laporan
            'laporan.view', 'laporan.export',

            // User Management
            'user.view', 'user.create', 'user.edit', 'user.delete',

            // Activity Log
            'log.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ---- Roles ----

        // 1. Staff Inventori
        $staffInventori = Role::firstOrCreate(['name' => 'staff_inventori', 'guard_name' => 'web']);
        $staffInventori->syncPermissions([
            'barang.view', 'barang.create', 'barang.edit', 'barang.delete', 'barang.import', 'barang.export',
            'aset.view', 'aset.create', 'aset.edit', 'aset.delete', 'aset.export',
            'supplier.view',
            'sr.view',
            'dr.view', 'dr.create', 'dr.edit', 'dr.delete',
            'pr.view', 'pr.create', 'pr.edit', 'pr.delete',
            'po.view',
            'rr.view', 'rr.create', 'rr.edit', 'rr.delete',
            'retur.view', 'retur.create', 'retur.edit', 'retur.delete',
            'opname.view', 'opname.create', 'opname.edit', 'opname.delete',
            'laporan.view', 'laporan.export',
        ]);

        // 2. Head Inventori
        $headInventori = Role::firstOrCreate(['name' => 'head_inventori', 'guard_name' => 'web']);
        $headInventori->syncPermissions([
            'barang.view', 'aset.view', 'supplier.view',
            'sr.view',
            'dr.view', 'dr.approve', 'dr.reject',
            'pr.view', 'pr.approve', 'pr.reject',
            'rr.view', 'rr.approve', 'rr.reject',
            'retur.view', 'retur.approve', 'retur.reject',
            'opname.view',
            'laporan.view', 'laporan.export',
            'log.view',
        ]);

        // 3. Staff Unit Peminjam
        $staffUnit = Role::firstOrCreate(['name' => 'staff_unit', 'guard_name' => 'web']);
        $staffUnit->syncPermissions([
            'sr.view', 'sr.create', 'sr.edit', 'sr.delete',
            'dr.view',
            'retur.view', 'retur.create',
        ]);

        // 4. Head Unit Peminjam
        $headUnit = Role::firstOrCreate(['name' => 'head_unit', 'guard_name' => 'web']);
        $headUnit->syncPermissions([
            'sr.view', 'sr.approve', 'sr.reject',
            'dr.view',
        ]);

        // 5. Staff Purchasing
        $staffPurchasing = Role::firstOrCreate(['name' => 'staff_purchasing', 'guard_name' => 'web']);
        $staffPurchasing->syncPermissions([
            'pr.view',
            'po.view', 'po.create', 'po.edit', 'po.delete',
            'supplier.view',
        ]);

        // 6. Head Purchasing
        $headPurchasing = Role::firstOrCreate(['name' => 'head_purchasing', 'guard_name' => 'web']);
        $headPurchasing->syncPermissions([
            'po.view', 'po.approve_head', 'po.reject_head',
            'supplier.view',
        ]);

        // 7. Finance
        $finance = Role::firstOrCreate(['name' => 'finance', 'guard_name' => 'web']);
        $finance->syncPermissions([
            'po.view', 'po.approve_finance', 'po.reject_finance',
            'rr.view', 'rr.approve', 'rr.reject',
            'laporan.view', 'laporan.export',
        ]);

        // 8. Supplier
        $supplier = Role::firstOrCreate(['name' => 'supplier', 'guard_name' => 'web']);
        $supplier->syncPermissions([
            'po.view',
            'retur.view',
            'supplier.view',
            'supplier.edit',
        ]);

        $this->command->info('Roles and permissions seeded successfully.');
    }
}
