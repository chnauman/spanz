<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = \App\Models\Role::firstOrCreate(
            ['name' => 'Admin'],
            ['description' => 'Full system access with all permissions']
        );

        $buyerRole = \App\Models\Role::firstOrCreate(
            ['name' => 'Buyer'],
            ['description' => 'Can create and manage tenders']
        );

        $supplierRole = \App\Models\Role::firstOrCreate(
            ['name' => 'Supplier'],
            ['description' => 'Can view and bid on tenders']
        );

        $subSupplierRole = \App\Models\Role::firstOrCreate(
            ['name' => 'Sub Supplier'],
            ['description' => 'Limited supplier access under parent supplier']
        );

        $guestRole = \App\Models\Role::firstOrCreate(
            ['name' => 'Guest'],
            ['description' => 'Limited access to public areas']
        );

        // Assign permissions to roles
        $this->assignPermissionsToRole($adminRole, [
            'view-users', 'create-users', 'edit-users', 'delete-users', 'approve-users',
            'view-categories', 'create-categories', 'edit-categories', 'delete-categories',
            'view-subscriptions', 'create-subscriptions', 'edit-subscriptions', 'delete-subscriptions',
            'view-credits', 'manage-credits',
            'view-tenders', 'create-tenders', 'edit-tenders', 'delete-tenders', 'bid-tenders',
            'access-admin-dashboard', 'access-buyer-dashboard', 'access-supplier-dashboard',
        ]);

        $this->assignPermissionsToRole($buyerRole, [
            'view-tenders', 'create-tenders', 'edit-tenders', 'delete-tenders',
            'access-buyer-dashboard',
        ]);

        $this->assignPermissionsToRole($supplierRole, [
            'view-tenders', 'bid-tenders',
            'access-supplier-dashboard',
        ]);

        $this->assignPermissionsToRole($subSupplierRole, [
            'view-tenders', 'bid-tenders',
            'access-supplier-dashboard',
        ]);

        // Guest role has no permissions by default
    }

    private function assignPermissionsToRole($role, $permissionSlugs)
    {
        foreach ($permissionSlugs as $slug) {
            $permission = \App\Models\Permission::where('slug', $slug)->first();
            if ($permission) {
                $role->givePermissionTo($permission);
            }
        }
    }
}
