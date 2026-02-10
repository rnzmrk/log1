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
            $table->integer('quantity')->nullable()->after('received_units');
            $table->string('department')->nullable()->after('quality');
            $table->string('received_by')->nullable()->after('department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inbound_logistics', function (Blueprint $table) {
            $table->dropColumn(['quantity', 'department', 'received_by']);
        });
    }
};
