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
        Schema::table('inventories', function (Blueprint $table) {
            // Make location nullable
            $table->string('location')->nullable()->change();
            
            // Update status enum to include new statuses
            $table->enum('status', ['On Stock', 'Low Stock', 'Out of Stock', 'Moved', 'Returned'])->default('On Stock')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            // Make location not nullable again
            $table->string('location')->nullable(false)->change();
            
            // Revert status enum to original values
            $table->enum('status', ['In Stock', 'Low Stock', 'Out of Stock'])->default('In Stock')->change();
        });
    }
};
