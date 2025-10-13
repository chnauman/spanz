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
        Schema::table('tenders', function (Blueprint $table) {
            // Add missing fields if they don't exist
            if (!Schema::hasColumn('tenders', 'request_type')) {
                $table->string('request_type')->nullable()->after('title');
            }
            if (!Schema::hasColumn('tenders', 'categories')) {
                $table->json('categories')->nullable()->after('contact_phone');
            }
            if (!Schema::hasColumn('tenders', 'attachments')) {
                $table->json('attachments')->nullable()->after('categories');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->dropColumn(['request_type', 'categories', 'attachments']);
        });
    }
};
