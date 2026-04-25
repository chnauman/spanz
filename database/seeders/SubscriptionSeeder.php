<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subscriptions = [
            [
                'name' => 'Buyer',
                'description' => 'Buyer Only',
                'features' => [
                    'Post unlimited Purchase requests and RFXs.',
                    'Get multiple supplier quotes for competitive pricing.',
                    '0 credit to quote on projects and RFXs.',
                    'Interact directly with suppliers.',
                ],
                'price' => 0.00,
                'credits_per_month' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Starter',
                'description' => 'Buyer + Supplier',
                'features' => [
                    'All options as a Buyer',
                    'X credit to quote on projects and RFXs.',
                    'Choose to get email notification when a RFX is posted by a Buyer',
                    'Interact directly with Buyers and Suppliers',
                ],
                'price' => 38.00,
                'credits_per_month' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Professional',
                'description' => 'Buyer + Supplier',
                'features' => [
                    'All options as a Buyer',
                    '30% discount on our regular document drafting, review & risk analysis services.',
                    'XX credit to quote on projects and RFXs.',
                    'Choose to get email notification when a RFX is posted by a Buyer',
                    'Interact directly with Buyers and Suppliers',
                ],
                'price' => 88.00,
                'credits_per_month' => 20,
                'is_active' => true,
            ],
            [
                'name' => 'Enterprise',
                'description' => 'Buyer + Supplier',
                'features' => [
                    'All options as a Buyer',
                    '30% discount on our regular document drafting, review & risk analysis services.',
                    'Unlimited credit to quote on projects and RFXs.',
                    'Choose to get email notification when a RFX is posted by a Buyer',
                    'Interact directly with Buyers and Suppliers',
                ],
                'price' => 128.00,
                'credits_per_month' => -1, // -1 for unlimited
                'is_active' => true,
            ],
        ];

        foreach ($subscriptions as $subscription) {
            \App\Models\Subscription::updateOrCreate(
                ['name' => $subscription['name']],
                $subscription
            );
        }

        // Disable legacy plans after introducing the new pricing structure.
        \App\Models\Subscription::whereIn('name', ['Basic', 'Pro'])->update(['is_active' => false]);
    }
}
