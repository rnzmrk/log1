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
        Schema::create('return_refunds', function (Blueprint $table) {
            $table->id();
            $table->string('return_id')->unique();
            $table->string('order_number');
            $table->string('customer_name');
            $table->string('product_name');
            $table->string('sku');
            $table->integer('quantity');
            $table->decimal('refund_amount', 10, 2);
            $table->enum('return_reason', ['Defective', 'Wrong Item', 'Damaged', 'Not Satisfied', 'Other']);
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Processed', 'Refunded'])->default('Pending');
            $table->date('return_date');
            $table->date('refund_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('refund_method')->nullable(); // Bank Transfer, Credit Card, Store Credit
            $table->string('tracking_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_refunds');
    }
};
