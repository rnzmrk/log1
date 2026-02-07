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
        Schema::create('asset_maintenances', function (Blueprint $table) {
            $table->id();
            $table->string('maintenance_number')->unique();
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');
            $table->string('asset_tag');
            $table->string('asset_name');
            $table->enum('maintenance_type', ['Preventive', 'Corrective', 'Emergency', 'Predictive', 'Calibration'])->default('Corrective');
            $table->enum('status', ['Scheduled', 'In Progress', 'Completed', 'On Hold', 'Cancelled'])->default('Scheduled');
            $table->enum('priority', ['Low', 'Medium', 'High', 'Urgent'])->default('Medium');
            $table->date('scheduled_date')->nullable();
            $table->datetime('start_time')->nullable();
            $table->datetime('end_time')->nullable();
            $table->text('problem_description')->nullable();
            $table->text('work_performed')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('parts_cost', 12, 2)->default(0);
            $table->decimal('labor_cost', 12, 2)->default(0);
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->string('technician_name')->nullable();
            $table->string('technician_email')->nullable();
            $table->string('technician_phone')->nullable();
            $table->text('next_maintenance_notes')->nullable();
            $table->date('next_maintenance_date')->nullable();
            $table->string('performed_by')->nullable();
            $table->string('approved_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_maintenances');
    }
};
