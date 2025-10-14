<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the columns exist before trying to access them
        if (!Schema::hasColumn('tenders', 'categories') || !Schema::hasColumn('tenders', 'attachments')) {
            // If columns don't exist, skip this migration
            return;
        }

        // First, let's clean up the existing data to ensure it's valid JSON
        $tenders = DB::table('tenders')->select('id', 'categories', 'attachments')->get();

        foreach ($tenders as $tender) {
            $updates = [];

            // Clean up categories column
            if ($tender->categories) {
                $categories = $this->cleanJsonData($tender->categories);
                $updates['categories'] = $categories;
            } else {
                $updates['categories'] = null;
            }

            // Clean up attachments column
            if ($tender->attachments) {
                $attachments = $this->cleanJsonData($tender->attachments);
                $updates['attachments'] = $attachments;
            } else {
                $updates['attachments'] = null;
            }

            // Update the record with cleaned data
            if (!empty($updates)) {
                DB::table('tenders')->where('id', $tender->id)->update($updates);
            }
        }

        // Now change the column types to JSON
        Schema::table('tenders', function (Blueprint $table) {
            $table->json('categories')->nullable()->change();
            $table->json('attachments')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if the columns exist before trying to modify them
        if (Schema::hasColumn('tenders', 'categories') && Schema::hasColumn('tenders', 'attachments')) {
            Schema::table('tenders', function (Blueprint $table) {
                $table->text('categories')->nullable()->change();
                $table->text('attachments')->nullable()->change();
            });
        }
    }

    /**
     * Clean and validate JSON data
     */
    private function cleanJsonData($data)
    {
        if (empty($data)) {
            return null;
        }

        // If it's already valid JSON, return it
        $decoded = json_decode($data, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $data;
        }

        // If it's a string that looks like a comma-separated list, convert to JSON array
        if (is_string($data) && !empty(trim($data))) {
            // Handle comma-separated values
            $items = array_map('trim', explode(',', $data));
            $items = array_filter($items); // Remove empty items

            if (!empty($items)) {
                return json_encode($items);
            }
        }

        // If it's a single value, wrap it in an array
        if (is_string($data) && !empty(trim($data))) {
            return json_encode([trim($data)]);
        }

        // Default to empty array
        return json_encode([]);
    }
};
