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
        Schema::create('vehicle_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reservation_id')->unique();
            $table->unsignedBigInteger('vehicle_id');
            $table->string('reserved_by');
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->text('purpose');
            $table->string('status')->default('Pending');
            $table->string('department');
            $table->json('api_data')->nullable();
            $table->string('request_status')->default('Pending');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_requests');
    }
};
