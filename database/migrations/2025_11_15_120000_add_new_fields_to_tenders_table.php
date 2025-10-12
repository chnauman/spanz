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
            $table->string('request_type')->nullable()->after('title');
            $table->string('contact_email')->nullable()->after('requirements');
            $table->string('contact_phone')->nullable()->after('contact_email');
            $table->json('categories')->nullable()->after('contact_phone'); // Store dynamic categories as JSON
            $table->json('attachments')->nullable()->after('categories'); // Store file paths as JSON
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->dropColumn(['request_type', 'contact_email', 'contact_phone', 'categories', 'attachments']);
        });
    }
};
