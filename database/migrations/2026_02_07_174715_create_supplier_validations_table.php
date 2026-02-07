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
        Schema::create('supplier_validations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('vendors')->onDelete('cascade');
            $table->string('validation_type'); // business_license, tax_clearance, insurance, etc.
            $table->string('document_number')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('status'); // valid, expired, pending, rejected
            $table->text('notes')->nullable();
            $table->string('document_path')->nullable();
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
            
            $table->index(['supplier_id', 'validation_type']);
            $table->index(['status', 'expiry_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_validations');
    }
};
