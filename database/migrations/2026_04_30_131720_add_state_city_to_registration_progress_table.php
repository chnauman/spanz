<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registration_progress', function (Blueprint $table) {
            $table->string('state')->nullable()->after('country');
            $table->string('city')->nullable()->after('state');
        });
    }

    public function down(): void
    {
        Schema::table('registration_progress', function (Blueprint $table) {
            $table->dropColumn(['state', 'city']);
        });
    }
};
