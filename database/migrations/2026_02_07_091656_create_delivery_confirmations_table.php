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
        Schema::create('delivery_confirmations', function (Blueprint $table) {
            $table->id();
            $table->string('confirmation_number')->unique();
            $table->string('order_number')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('recipient_name');
            $table->string('recipient_email')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->text('delivery_address');
            $table->string('delivery_city');
            $table->string('delivery_state');
            $table->string('delivery_zipcode');
            $table->string('delivery_country')->default('USA');
            $table->enum('delivery_type', ['Standard', 'Express', 'Overnight', 'Same Day', 'Scheduled'])->default('Standard');
            $table->enum('status', ['Pending', 'Out for Delivery', 'Delivered', 'Failed', 'Cancelled'])->default('Pending');
            $table->date('scheduled_delivery_date')->nullable();
            $table->datetime('actual_delivery_time')->nullable();
            $table->string('delivery_notes')->nullable();
            $table->text('special_instructions')->nullable();
            $table->string('carrier_name')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->string('signature_image_url')->nullable();
            $table->decimal('package_value', 12, 2)->nullable();
            $table->integer('package_count')->default(1);
            $table->enum('priority', ['Low', 'Medium', 'High', 'Urgent'])->default('Medium');
            $table->string('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_confirmations');
    }
};
