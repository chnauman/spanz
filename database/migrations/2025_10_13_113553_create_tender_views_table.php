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
        Schema::create('tender_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tender_id')->constrained()->onDelete('cascade');
            $table->timestamp('viewed_at');
            $table->integer('credits_deducted')->default(0);
            $table->integer('credit_cost_per_view')->default(1);
            $table->timestamps();
            
            // Ensure a user can only view a tender once (prevent duplicate credit deduction)
            $table->unique(['user_id', 'tender_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_views');
    }
};
