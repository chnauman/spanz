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
        Schema::create('registration_progress', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password'); // Hashed password
            $table->string('registered_business_name')->nullable();
            $table->string('country')->nullable();
            $table->text('business_address')->nullable();
            $table->string('full_name')->nullable(); // Point of Contact
            $table->string('title_position')->nullable();
            $table->string('cell_mobile')->nullable(); // Country code and number
            $table->string('whatsapp_wechat')->nullable();
            $table->boolean('email_verified')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->onDelete('set null');
            $table->integer('current_step')->default(1); // 1, 2, or 3
            $table->boolean('registration_complete')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_progress');
    }
};
