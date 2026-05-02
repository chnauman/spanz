<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Split legacy single field into tender headline (`title`) vs product/service line (`product_or_service`).
     * Existing rows: copy current title into product_or_service; clear title so lists use description-led card titles until buyers edit.
     */
    public function up(): void
    {
        Schema::table('tenders', function (Blueprint $table) {
            if (!Schema::hasColumn('tenders', 'product_or_service')) {
                $table->string('product_or_service', 255)->nullable()->after('title');
            }
        });

        if (!Schema::hasColumn('tenders', 'product_or_service')) {
            return;
        }

        DB::table('tenders')
            ->orderBy('id')
            ->select(['id', 'title'])
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    DB::table('tenders')->where('id', $row->id)->update([
                        'product_or_service' => $row->title,
                        'title' => '',
                    ]);
                }
            }, 'id');
    }

    public function down(): void
    {
        if (!Schema::hasColumn('tenders', 'product_or_service')) {
            return;
        }

        DB::table('tenders')
            ->orderBy('id')
            ->select(['id', 'title', 'product_or_service'])
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    $headline = (string) ($row->title ?? '');
                    $product = (string) ($row->product_or_service ?? '');
                    DB::table('tenders')->where('id', $row->id)->update([
                        'title' => $headline !== '' ? $headline : $product,
                    ]);
                }
            }, 'id');

        Schema::table('tenders', function (Blueprint $table) {
            $table->dropColumn('product_or_service');
        });
    }
};
