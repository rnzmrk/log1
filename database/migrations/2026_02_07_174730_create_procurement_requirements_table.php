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
        Schema::create('procurement_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('requirement_number')->unique();
            $table->string('title');
            $table->text('description');
            $table->string('category');
            $table->string('priority'); // low, medium, high, critical
            $table->string('type'); // goods, services, works, consultancy
            $table->decimal('estimated_budget', 15, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->date('required_date');
            $table->string('department');
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->string('status'); // draft, submitted, approved, rejected, procured, cancelled
            $table->text('rejection_reason')->nullable();
            $table->text('specifications')->nullable();
            $table->text('delivery_terms')->nullable();
            $table->text('payment_terms')->nullable();
            $table->foreignId('assigned_supplier_id')->nullable()->constrained('vendors')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['status', 'priority']);
            $table->index(['required_date']);
            $table->index(['department']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_requirements');
    }
};
