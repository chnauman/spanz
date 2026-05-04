<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CompanyDetail;
use App\Models\User;
use App\Models\UserInterest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SupplierDirectoryDemoSeeder extends Seeder
{
    /**
     * Seed demo suppliers for the supplier directory (and document share testing).
     * Requires categories from CategorySeeder. Password for all demo accounts: password
     */
    public function run(): void
    {
        $category = Category::query()
            ->where('is_active', true)
            ->whereNotNull('parent_category_id')
            ->orderBy('id')
            ->first();

        if (! $category) {
            $category = Category::query()->where('is_active', true)->orderBy('id')->first();
        }

        if (! $category) {
            $this->command?->warn('SupplierDirectoryDemoSeeder: no category found. Run CategorySeeder first.');

            return;
        }

        $demos = [
            ['suffix' => 1, 'company' => 'Aurora Civil Pty Ltd', 'country' => 'Australia', 'state' => 'New South Wales', 'city' => 'Sydney'],
            ['suffix' => 2, 'company' => 'Bayview Electrical Services', 'country' => 'Australia', 'state' => 'Victoria', 'city' => 'Melbourne'],
            ['suffix' => 3, 'company' => 'Pacific Steel Fabrication', 'country' => 'New Zealand', 'state' => 'Auckland', 'city' => 'Auckland'],
            ['suffix' => 4, 'company' => 'Northstar Logistics NZ', 'country' => 'New Zealand', 'state' => 'Wellington', 'city' => 'Wellington'],
            ['suffix' => 5, 'company' => 'Singapore Marine Supplies', 'country' => 'Singapore', 'state' => 'Singapore', 'city' => 'Singapore'],
            ['suffix' => 6, 'company' => 'USA Industrial Parts LLC', 'country' => 'USA', 'state' => 'Texas', 'city' => 'Houston'],
        ];

        foreach ($demos as $row) {
            $email = 'supplier-demo-' . $row['suffix'] . '@spanz.test';
            $user = User::query()->updateOrCreate(
                ['email' => $email],
                [
                    'name' => 'Demo Contact ' . $row['suffix'],
                    'password' => Hash::make('password'),
                    'role' => 'supplier',
                    'is_approved' => true,
                    'email_verified_at' => now(),
                ]
            );

            CompanyDetail::query()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'company_name' => $row['company'],
                    'abn' => null,
                    'registration_number' => null,
                    'address' => $row['city'] . ' HQ',
                    'city' => $row['city'],
                    'state' => $row['state'],
                    'postal_code' => '2000',
                    'country' => $row['country'],
                    'phone' => '+61 400 000 ' . str_pad((string) (200 + $row['suffix']), 3, '0', STR_PAD_LEFT),
                    'website' => 'https://example.com/supplier-demo-' . $row['suffix'],
                    'description' => 'Seeded demo supplier for directory and document sharing tests.',
                    'headquarter_location' => $row['city'] . ', ' . $row['country'],
                ]
            );

            UserInterest::query()->firstOrCreate(
                [
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                ],
                [
                    'min_budget' => null,
                    'max_budget' => null,
                ]
            );
        }

        $this->command?->info('SupplierDirectoryDemoSeeder: created/updated 6 suppliers (supplier-demo-1@spanz.test … supplier-demo-6@spanz.test, password: password).');
    }
}
