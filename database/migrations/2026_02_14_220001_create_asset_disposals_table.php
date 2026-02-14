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
        Schema::create('asset_disposals', function (Blueprint $table) {
            $table->id();
            $table->string('disposal_number')->unique();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->string('asset_name');
            $table->text('details')->nullable();
            $table->date('date')->nullable();
            $table->string('duration')->nullable();
            $table->string('department')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->enum('status', ['pending', 'approved', 'completed', 'rejected'])->default('pending');
            $table->string('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_disposals');
    }
};
