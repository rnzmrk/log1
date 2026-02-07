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
        Schema::create('inbound_logistics', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_id')->unique();
            $table->string('po_number');
            $table->string('supplier');
            $table->integer('expected_units');
            $table->integer('received_units')->nullable();
            $table->enum('quality', ['Good', 'Pending'])->default('Pending');
            $table->enum('status', ['In Progress', 'Verified', 'Putaway Complete'])->default('In Progress');
            $table->date('expected_date');
            $table->date('received_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inbound_logistics');
    }
};
