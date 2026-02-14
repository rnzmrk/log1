<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `assets` MODIFY `status` ENUM('Available','In Use','Under Maintenance','Requested','Disposal','Disposed','Retired','Lost','Damaged') NOT NULL DEFAULT 'Available'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `assets` MODIFY `status` ENUM('Available','In Use','Under Maintenance','Retired','Lost','Damaged') NOT NULL DEFAULT 'Available'");
    }
};
