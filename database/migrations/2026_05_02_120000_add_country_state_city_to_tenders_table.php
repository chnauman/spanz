<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->string('country_code', 40)->nullable()->after('location');
            $table->foreignId('state_id')->nullable()->after('country_code')->constrained()->nullOnDelete();
            $table->foreignId('city_id')->nullable()->after('state_id')->constrained()->nullOnDelete();
        });

        $slugs = [
            'australia',
            'new-zealand',
            'singapore',
            'usa',
            'uk',
            'canada',
            'germany',
            'france',
        ];

        foreach ($slugs as $slug) {
            DB::table('tenders')
                ->whereNull('country_code')
                ->where('location', $slug)
                ->update(['country_code' => $slug]);
        }
    }

    public function down(): void
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->dropForeign(['state_id']);
            $table->dropForeign(['city_id']);
            $table->dropColumn(['country_code', 'state_id', 'city_id']);
        });
    }
};
