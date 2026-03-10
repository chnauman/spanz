<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('company_details', function (Blueprint $table) {
            // Basic company profile fields
            $table->string('headquarter_location')->nullable();
            $table->string('employees_range')->nullable(); // e.g. "1-10", "11-30"

            // Business industries & categories
            $table->json('main_industries')->nullable();        // up to 3 main industries
            $table->json('subcategories_by_industry')->nullable(); // per-industry subcategories (up to 6 each)

            // Company profile
            $table->json('company_types')->nullable();          // multiple company type checkboxes
            $table->string('yearly_revenue_range')->nullable(); // dropdown text

            // Quality certifications
            $table->json('quality_certifications')->nullable();

            // Freeform lists
            $table->text('brands_represented')->nullable();
            $table->text('industry_awards')->nullable();
            $table->text('industry_memberships')->nullable();
            $table->text('unique_value_propositions')->nullable();
            $table->text('major_projects')->nullable();

            // Market presence - delivery capabilities and reps/office locations
            $table->json('delivery_capabilities')->nullable();
            $table->json('office_locations')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_details', function (Blueprint $table) {
            $table->dropColumn([
                'headquarter_location',
                'employees_range',
                'main_industries',
                'subcategories_by_industry',
                'company_types',
                'yearly_revenue_range',
                'quality_certifications',
                'brands_represented',
                'industry_awards',
                'industry_memberships',
                'unique_value_propositions',
                'major_projects',
                'delivery_capabilities',
                'office_locations',
            ]);
        });
    }
};

