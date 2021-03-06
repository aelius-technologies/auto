<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder{
    public function run(){
        $gm_permissions = [
            'user-create',
            'user-edit',
            'user-view',
            'user-delete'
        ];

        $branch_permissions = [
            'transfer-create',
            'transfer-view',
            'transfer-edit',
            'transfer-delete'
        ];

        $admin_permissions = [
            'role-create',
            'role-edit',
            'role-view',
            'role-delete',
            'permission-create',
            'permission-edit',
            'permission-view',
            'permission-delete',
            'access-create',
            'access-view',
            'access-edit',
            'access-delete',
            'accessories-create',
            'accessories-view',
            'accessories-edit',
            'accessories-delete',
            'approval-create',
            'approval-view',
            'approval-edit',
            'approval-delete',
            'branches-create',
            'branches-view',
            'branches-edit',
            'branches-delete',
            'car_exchange-create',
            'car_exchange-view',
            'car_exchange-edit',
            'car_exchange-delete',
            'car_exchange_category-create',
            'car_exchange_category-view',
            'car_exchange_category-edit',
            'car_exchange_category-delete',
            'car_exchange_product-create',
            'car_exchange_product-view',
            'car_exchange_product-edit',
            'car_exchange_product-delete',
            'categories-create',
            'categories-view',
            'categories-edit',
            'categories-delete',
            'department-create',
            'department-view',
            'department-edit',
            'department-delete',
            'extand_warranties-create',
            'extand_warranties-view',
            'extand_warranties-edit',
            'extand_warranties-delete',
            'fasttags-create',
            'fasttags-view',
            'fasttags-edit',
            'fasttags-delete',
            'finance-create',
            'finance-view',
            'finance-edit',
            'finance-delete',
            'insurance-create',
            'insurance-view',
            'insurance-edit',
            'insurance-delete',
            'lead-create',
            'lead-view',
            'lead-edit',
            'lead-delete',
            'obf-create',
            'obf-view',
            'obf-edit',
            'obf-delete',
            'orders-create',
            'orders-view',
            'orders-edit',
            'orders-delete',
            'taxes-create',
            'taxes-view',
            'taxes-edit',
            'taxes-delete',
            'allocation-create',
            'allocation-view',
            'allocation-edit',
            'allocation-delete',
            'products-create',
            'products-view',
            'products-edit',
            'products-delete',
            'inventory-create',
            'inventory-view',
            'inventory-edit',
            'inventory-delete',
            'account_approval-delete',
            'account_approval-view',
            'account_approval-edit',
            'account_approval-create',
            'cash_receipt-delete',
            'cash_receipt-view',
            'cash_receipt-edit',
            'cash_receipt-create',
            'cash_receipt-generate_gate_pass',
            'obf_approval-delete',
            'obf_approval-view',
            'obf_approval-edit',
            'obf_approval-create',
        ];

        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = array_merge($gm_permissions, $branch_permissions, $admin_permissions);

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        $gm = Role::findByName('gm');
        $gm->givePermissionTo($gm_permissions);

        $branch = Role::findByName('branch');
        $branch->givePermissionTo($branch_permissions);

        $admin = Role::findByName('admin');
        $admin->givePermissionTo($permissions);
    }
}
