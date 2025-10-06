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
                'name' => 'Basic',
                'description' => 'Free plan with no credits',
                'price' => 0.00,
                'credits_per_month' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Pro',
                'description' => 'Professional plan with 20 credits per month',
                'price' => 29.99,
                'credits_per_month' => 20,
                'is_active' => true,
            ],
            [
                'name' => 'Enterprise',
                'description' => 'Enterprise plan with unlimited credits',
                'price' => 99.99,
                'credits_per_month' => -1, // -1 for unlimited
                'is_active' => true,
            ],
        ];

        foreach ($subscriptions as $subscription) {
            \App\Models\Subscription::create($subscription);
        }
    }
}
