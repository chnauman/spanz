<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // User Management
            ['name' => 'View Users', 'slug' => 'view-users', 'description' => 'View all users'],
            ['name' => 'Create Users', 'slug' => 'create-users', 'description' => 'Create new users'],
            ['name' => 'Edit Users', 'slug' => 'edit-users', 'description' => 'Edit existing users'],
            ['name' => 'Delete Users', 'slug' => 'delete-users', 'description' => 'Delete users'],
            ['name' => 'Approve Users', 'slug' => 'approve-users', 'description' => 'Approve pending users'],
            
            // Category Management
            ['name' => 'View Categories', 'slug' => 'view-categories', 'description' => 'View all categories'],
            ['name' => 'Create Categories', 'slug' => 'create-categories', 'description' => 'Create new categories'],
            ['name' => 'Edit Categories', 'slug' => 'edit-categories', 'description' => 'Edit existing categories'],
            ['name' => 'Delete Categories', 'slug' => 'delete-categories', 'description' => 'Delete categories'],
            
            // Subscription Management
            ['name' => 'View Subscriptions', 'slug' => 'view-subscriptions', 'description' => 'View all subscriptions'],
            ['name' => 'Create Subscriptions', 'slug' => 'create-subscriptions', 'description' => 'Create new subscriptions'],
            ['name' => 'Edit Subscriptions', 'slug' => 'edit-subscriptions', 'description' => 'Edit existing subscriptions'],
            ['name' => 'Delete Subscriptions', 'slug' => 'delete-subscriptions', 'description' => 'Delete subscriptions'],
            
            // Credit Management
            ['name' => 'View Credits', 'slug' => 'view-credits', 'description' => 'View user credits'],
            ['name' => 'Manage Credits', 'slug' => 'manage-credits', 'description' => 'Add/remove user credits'],
            
            // Tender Management
            ['name' => 'View Tenders', 'slug' => 'view-tenders', 'description' => 'View all tenders'],
            ['name' => 'Create Tenders', 'slug' => 'create-tenders', 'description' => 'Create new tenders'],
            ['name' => 'Edit Tenders', 'slug' => 'edit-tenders', 'description' => 'Edit existing tenders'],
            ['name' => 'Delete Tenders', 'slug' => 'delete-tenders', 'description' => 'Delete tenders'],
            ['name' => 'Bid on Tenders', 'slug' => 'bid-tenders', 'description' => 'Submit bids on tenders'],
            
            // Dashboard Access
            ['name' => 'Access Admin Dashboard', 'slug' => 'access-admin-dashboard', 'description' => 'Access admin dashboard'],
            ['name' => 'Access Buyer Dashboard', 'slug' => 'access-buyer-dashboard', 'description' => 'Access buyer dashboard'],
            ['name' => 'Access Supplier Dashboard', 'slug' => 'access-supplier-dashboard', 'description' => 'Access supplier dashboard'],
        ];

        foreach ($permissions as $permission) {
            \App\Models\Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }
    }
}
