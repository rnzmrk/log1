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
        Schema::create('supplier_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('vendors')->onDelete('cascade');
            $table->string('verification_type'); // site_visit, background_check, financial_audit, etc.
            $table->date('verification_date');
            $table->string('status'); // passed, failed, pending, scheduled
            $table->integer('score')->nullable(); // 0-100 score
            $table->text('findings')->nullable();
            $table->text('recommendations')->nullable();
            $table->string('report_path')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('scheduled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamps();
            
            $table->index(['supplier_id', 'verification_type']);
            $table->index(['status', 'verification_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_verifications');
    }
};
