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
        Schema::table('return_refunds', function (Blueprint $table) {
            // Make existing fields nullable
            $table->string('order_number')->nullable()->change();
            $table->string('customer_name')->nullable()->change();
            $table->string('product_name')->nullable()->change();
            $table->integer('quantity')->nullable()->change();
            $table->decimal('refund_amount', 10, 2)->nullable()->change();
            $table->string('return_reason')->nullable()->change();
            
            // Add missing fields
            $table->string('po_number')->nullable()->after('return_reason');
            $table->string('department')->nullable()->after('po_number');
            $table->string('item_name')->nullable()->after('department');
            $table->integer('stock')->nullable()->after('item_name');
            $table->string('supplier')->nullable()->after('stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('return_refunds', function (Blueprint $table) {
            // Drop added fields
            $table->dropColumn(['po_number', 'department', 'item_name', 'stock', 'supplier']);
            
            // Make existing fields not nullable (revert)
            $table->string('order_number')->nullable(false)->change();
            $table->string('customer_name')->nullable(false)->change();
            $table->string('product_name')->nullable(false)->change();
            $table->integer('quantity')->nullable(false)->change();
            $table->decimal('refund_amount', 10, 2)->nullable(false)->change();
            $table->string('return_reason')->nullable(false)->change();
        });
    }
};
