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
        Schema::create('project_plannings', function (Blueprint $table) {
            $table->id();
            $table->string('project_number')->unique();
            $table->string('project_name');
            $table->text('project_description');
            $table->enum('project_type', ['Construction', 'Renovation', 'Maintenance', 'Installation', 'Inspection', 'Other'])->default('Construction');
            $table->enum('priority', ['Low', 'Medium', 'High', 'Critical'])->default('Medium');
            $table->enum('status', ['Draft', 'Submitted', 'Under Review', 'Approved', 'In Progress', 'On Hold', 'Completed', 'Cancelled'])->default('Draft');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('estimated_duration_days')->default(0);
            $table->integer('estimated_duration_weeks')->default(0);
            $table->integer('estimated_duration_months')->default(0);
            $table->string('project_address');
            $table->string('project_city');
            $table->string('project_state');
            $table->string('project_zipcode');
            $table->string('onsite_contact_person');
            $table->integer('engineers_required')->default(0);
            $table->integer('technicians_required')->default(0);
            $table->integer('laborers_required')->default(0);
            $table->boolean('needs_cranes')->default(false);
            $table->boolean('needs_power_tools')->default(false);
            $table->boolean('needs_safety_equipment')->default(false);
            $table->boolean('needs_measurement_tools')->default(false);
            $table->text('materials_required')->nullable();
            $table->decimal('estimated_budget', 12, 2)->default(0);
            $table->decimal('labor_cost', 12, 2)->default(0);
            $table->decimal('material_cost', 12, 2)->default(0);
            $table->decimal('equipment_rental_cost', 12, 2)->default(0);
            $table->decimal('other_expenses', 12, 2)->default(0);
            $table->string('requested_by');
            $table->string('department');
            $table->string('approved_by')->nullable();
            $table->date('approved_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_plannings');
    }
};
