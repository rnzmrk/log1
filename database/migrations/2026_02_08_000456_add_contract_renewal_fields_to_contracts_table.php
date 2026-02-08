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
        Schema::table('contracts', function (Blueprint $table) {
            $table->integer('renewal_count')->default(0)->after('renewal_date');
            $table->boolean('auto_renewal')->default(false)->after('renewal_count');
            $table->text('renewal_terms')->nullable()->after('auto_renewal');
            $table->string('renewed_by')->nullable()->after('renewal_terms');
            $table->string('terminated_by')->nullable()->after('renewed_by');
            $table->text('termination_reason')->nullable()->after('terminated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn([
                'renewal_count',
                'auto_renewal',
                'renewal_terms',
                'renewed_by',
                'terminated_by',
                'termination_reason'
            ]);
        });
    }
};
