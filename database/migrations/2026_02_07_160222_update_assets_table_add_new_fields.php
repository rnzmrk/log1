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
        Schema::table('assets', function (Blueprint $table) {
            // Add new fields
            $table->string('item_number')->unique()->after('id');
            $table->string('image')->nullable()->after('item_number');
            $table->string('item_code')->unique()->after('image');
            $table->string('item_name')->after('item_code');
            $table->date('date')->nullable()->after('status');
            
            // Add disposal fields
            $table->text('disposal_reason')->nullable()->after('specifications');
            $table->date('disposal_date')->nullable()->after('disposal_reason');
            $table->string('disposed_by')->nullable()->after('disposal_date');
            
            // Drop old asset_tag field if it exists
            if (Schema::hasColumn('assets', 'asset_tag')) {
                $table->dropColumn('asset_tag');
            }
            
            // Drop old asset_name field if it exists (we're using item_name now)
            if (Schema::hasColumn('assets', 'asset_name')) {
                $table->dropColumn('asset_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Drop new fields
            $table->dropColumn(['item_number', 'image', 'item_code', 'item_name', 'date', 'disposal_reason', 'disposal_date', 'disposed_by']);
            
            // Restore old fields
            $table->string('asset_tag')->unique()->after('id');
            $table->string('asset_name')->after('asset_tag');
        });
    }
};
