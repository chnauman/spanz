<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * The per-view credit cost is now resolved exclusively from the
     * tender_view_pricing_rules table based on the tender's budget range,
     * so the column on subscriptions is no longer used.
     */
    public function up(): void
    {
        if (Schema::hasColumn('subscriptions', 'credit_cost_per_view')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropColumn('credit_cost_per_view');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('subscriptions', 'credit_cost_per_view')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->integer('credit_cost_per_view')->default(1)->after('credits_per_month');
            });
        }
    }
};
