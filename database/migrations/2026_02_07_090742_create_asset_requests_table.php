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
        Schema::create('asset_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->string('asset_name');
            $table->string('asset_type');
            $table->string('category');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->enum('priority', ['Low', 'Medium', 'High', 'Urgent'])->default('Medium');
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Processing', 'Completed', 'Cancelled']);
            $table->enum('request_type', ['New', 'Replacement', 'Upgrade', 'Repair', 'Other']);
            $table->decimal('estimated_cost', 12, 2)->nullable();
            $table->date('request_date');
            $table->date('required_date')->nullable();
            $table->date('approved_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->text('justification')->nullable();
            $table->text('specifications')->nullable();
            $table->text('notes')->nullable();
            $table->string('requested_by');
            $table->string('department');
            $table->string('approved_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_requests');
    }
};
