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
        Schema::create('supply_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_id')->unique();
            $table->string('item_name');
            $table->string('category');
            $table->string('supplier');
            $table->integer('quantity_requested');
            $table->integer('quantity_approved')->nullable();
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('total_cost', 12, 2)->nullable();
            $table->enum('priority', ['Low', 'Medium', 'High', 'Urgent'])->default('Medium');
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Ordered', 'Received'])->default('Pending');
            $table->date('request_date');
            $table->date('needed_by_date');
            $table->date('approval_date')->nullable();
            $table->date('order_date')->nullable();
            $table->date('expected_delivery')->nullable();
            $table->text('notes')->nullable();
            $table->string('requested_by');
            $table->string('approved_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_requests');
    }
};
