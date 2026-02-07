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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->unique();
            $table->string('contract_name');
            $table->string('vendor');
            $table->string('vendor_contact')->nullable();
            $table->string('vendor_email')->nullable();
            $table->string('vendor_phone')->nullable();
            $table->enum('contract_type', ['Service', 'Supply', 'Maintenance', 'Consulting', 'Software License', 'Hardware Lease', 'Other']);
            $table->decimal('contract_value', 12, 2);
            $table->enum('status', ['Draft', 'Under Review', 'Active', 'Expired', 'Terminated', 'Renewed']);
            $table->enum('priority', ['Low', 'Medium', 'High', 'Urgent'])->default('Medium');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('renewal_date')->nullable();
            $table->text('description')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->text('notes')->nullable();
            $table->string('created_by');
            $table->string('approved_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
