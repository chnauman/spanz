<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Recover from a previously failed run (e.g. FK name too long): tables may exist without this migration being recorded.
        Schema::dropIfExists('supplier_document_share_files');
        Schema::dropIfExists('supplier_document_share_recipients');
        Schema::dropIfExists('supplier_document_shares');

        Schema::create('supplier_document_shares', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->text('message')->nullable();
            $table->timestamps();

            $table->foreign('sender_id', 'sds_sender_fk')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });

        Schema::create('supplier_document_share_recipients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_document_share_id');
            $table->unsignedBigInteger('recipient_user_id');
            $table->timestamps();

            $table->unique(['supplier_document_share_id', 'recipient_user_id'], 'share_recipient_unique');

            $table->foreign('supplier_document_share_id', 'sdsr_share_fk')
                ->references('id')
                ->on('supplier_document_shares')
                ->cascadeOnDelete();

            $table->foreign('recipient_user_id', 'sdsr_user_fk')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });

        Schema::create('supplier_document_share_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_document_share_id');
            $table->string('path');
            $table->string('original_name');
            $table->string('mime')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->timestamps();

            $table->foreign('supplier_document_share_id', 'sdsf_share_fk')
                ->references('id')
                ->on('supplier_document_shares')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_document_share_files');
        Schema::dropIfExists('supplier_document_share_recipients');
        Schema::dropIfExists('supplier_document_shares');
    }
};
