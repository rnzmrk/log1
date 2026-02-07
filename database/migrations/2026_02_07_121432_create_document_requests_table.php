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
        Schema::create('document_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->string('request_title');
            $table->text('description');
            $table->enum('priority', ['Low', 'Medium', 'High', 'Critical']);
            $table->enum('urgency', ['Normal (3-5 days)', 'Urgent (1-2 days)', 'Critical (Same day)']);
            $table->enum('document_type', ['Contract', 'Invoice', 'Receipt', 'Report', 'Certificate', 'License', 'Permit', 'Identification', 'Other']);
            $table->enum('document_category', ['Legal', 'Financial', 'HR', 'Operations', 'Compliance', 'Technical', 'Administrative']);
            $table->string('format_required');
            $table->integer('number_of_copies')->default(1);
            $table->string('date_range')->nullable();
            $table->string('reference_number')->nullable();
            $table->boolean('notarization_required')->default(false);
            $table->boolean('apostille_needed')->default(false);
            $table->boolean('translation_required')->default(false);
            $table->boolean('certified_true_copy')->default(false);
            $table->enum('delivery_method', ['Digital Download', 'Email Attachment', 'Office Pickup', 'Courier Delivery', 'Registered Mail']);
            $table->text('delivery_address')->nullable();
            $table->string('contact_person');
            $table->string('contact_number');
            $table->text('purpose');
            $table->string('project_name')->nullable();
            $table->string('cost_center')->nullable();
            $table->enum('status', ['Draft', 'Submitted', 'Under Review', 'Approved', 'Processing', 'Completed', 'Rejected', 'Cancelled']);
            $table->string('requested_by');
            $table->string('department');
            $table->date('request_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_requests');
    }
};
