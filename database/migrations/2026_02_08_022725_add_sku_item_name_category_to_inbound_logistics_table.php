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
        Schema::table('inbound_logistics', function (Blueprint $table) {
            $table->string('sku')->nullable()->after('po_number');
            $table->string('item_name')->nullable()->after('sku');
            $table->string('category')->nullable()->after('item_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inbound_logistics', function (Blueprint $table) {
            $table->dropColumn(['sku', 'item_name', 'category']);
        });
    }
};
