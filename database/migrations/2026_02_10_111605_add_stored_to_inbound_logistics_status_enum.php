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
        Schema::table('inbound_logistics', function (Blueprint $table) {
            $table->enum('status', ['Pending', 'In Transit', 'Delivered', 'Storage', 'Stored', 'In Progress', 'Verified', 'Putaway Complete'])->default('Pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inbound_logistics', function (Blueprint $table) {
            $table->enum('status', ['Pending', 'In Transit', 'Delivered', 'Storage', 'In Progress', 'Verified', 'Putaway Complete'])->default('Pending')->change();
        });
    }
};
