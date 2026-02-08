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
        Schema::table('outbound_logistics', function (Blueprint $table) {
            $table->enum('request_type', ['Supply Request', 'Department Supply', 'Customer Order', 'Other'])->default('Customer Order')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outbound_logistics', function (Blueprint $table) {
            $table->dropColumn('request_type');
        });
    }
};
