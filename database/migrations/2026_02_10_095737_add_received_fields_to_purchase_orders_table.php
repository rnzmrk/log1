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
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('received_by')->nullable()->after('approved_by');
            $table->timestamp('received_at')->nullable()->after('received_by');
            $table->string('rejected_by')->nullable()->after('received_at');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            $table->timestamp('approved_at')->nullable()->after('rejected_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn(['received_by', 'received_at', 'rejected_by', 'rejected_at', 'approved_at']);
        });
    }
};
