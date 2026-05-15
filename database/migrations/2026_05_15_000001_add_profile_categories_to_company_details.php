<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_details', function (Blueprint $table) {
            $table->json('profile_category_ids')->nullable()->after('subcategories_by_industry');
            $table->json('profile_subcategory_ids')->nullable()->after('profile_category_ids');
        });
    }

    public function down(): void
    {
        Schema::table('company_details', function (Blueprint $table) {
            $table->dropColumn(['profile_category_ids', 'profile_subcategory_ids']);
        });
    }
};
