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
        Schema::table('outbound_logistics', function (Blueprint $table) {
            $table->string('sku')->nullable()->after('shipment_id');
            $table->string('po_number')->nullable()->after('sku');
            $table->string('department')->nullable()->after('po_number');
            $table->string('supplier')->nullable()->after('department');
            $table->string('item_name')->nullable()->after('supplier');
            $table->string('address')->nullable()->after('destination');
            $table->string('contact')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outbound_logistics', function (Blueprint $table) {
            $table->dropColumn(['sku', 'po_number', 'department', 'supplier', 'item_name', 'address', 'contact']);
        });
    }
};
