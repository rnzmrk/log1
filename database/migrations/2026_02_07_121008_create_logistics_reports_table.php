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
        Schema::create('logistics_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number')->unique();
            $table->string('report_name');
            $table->enum('report_type', ['Delivery', 'Vehicle', 'Project', 'Performance', 'Financial', 'Inventory', 'Maintenance']);
            $table->enum('status', ['Completed', 'Processing', 'Scheduled', 'Failed', 'Cancelled']);
            $table->enum('priority', ['Low', 'Medium', 'High', 'Urgent']);
            $table->string('generated_by');
            $table->string('department');
            $table->date('report_date');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();
            $table->json('data_summary')->nullable();
            $table->string('file_path')->nullable();
            $table->integer('total_records')->default(0);
            $table->decimal('success_rate', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logistics_reports');
    }
};
