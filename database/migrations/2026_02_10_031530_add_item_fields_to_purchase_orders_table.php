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
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('item_name')->nullable()->after('shipping_address');
            $table->string('item_category')->nullable()->after('item_name');
            $table->integer('item_quantity')->nullable()->after('item_category');
            $table->decimal('unit_price', 12, 2)->nullable()->after('item_quantity');
            $table->decimal('total_cost', 12, 2)->nullable()->after('unit_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn(['item_name', 'item_category', 'item_quantity', 'unit_price', 'total_cost']);
        });
    }
};
