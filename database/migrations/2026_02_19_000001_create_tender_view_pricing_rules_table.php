<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tender_view_pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->decimal('budget_min', 15, 2);
            $table->decimal('budget_max', 15, 2)->nullable();
            $table->unsignedInteger('credits_cost')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'budget_min', 'budget_max']);
        });

        // Default buckets to match the current tender budget selector.
        // Admin can adjust credit costs anytime; existing unlocks remain grandfathered via tender_views.
        DB::table('tender_view_pricing_rules')->insert([
            ['budget_min' => 0, 'budget_max' => 1000, 'credits_cost' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['budget_min' => 1001, 'budget_max' => 5000, 'credits_cost' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['budget_min' => 5001, 'budget_max' => 10000, 'credits_cost' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['budget_min' => 10001, 'budget_max' => 30000, 'credits_cost' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['budget_min' => 30001, 'budget_max' => 50000, 'credits_cost' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['budget_min' => 50001, 'budget_max' => 100000, 'credits_cost' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['budget_min' => 100001, 'budget_max' => 500000, 'credits_cost' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['budget_min' => 500001, 'budget_max' => 1000000, 'credits_cost' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['budget_min' => 1000001, 'budget_max' => null, 'credits_cost' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_view_pricing_rules');
    }
};

