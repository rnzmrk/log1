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
        Schema::create('document_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number')->unique();
            $table->string('report_name');
            $table->string('report_type');
            $table->text('description')->nullable();
            $table->string('status')->default('Processing');
            $table->string('generated_by');
            $table->string('department');
            $table->date('report_date');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('parameters')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->integer('file_size')->nullable();
            $table->text('summary')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_reports');
    }
};
