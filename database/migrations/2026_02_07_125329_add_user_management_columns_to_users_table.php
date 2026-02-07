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
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_number')->unique()->nullable()->after('id');
            $table->string('role')->default('User')->after('email_verified_at');
            $table->string('department')->nullable()->after('role');
            $table->string('status')->default('Active')->after('department');
            $table->string('phone')->nullable()->after('status');
            $table->timestamp('last_login_at')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['user_number', 'role', 'department', 'status', 'phone', 'last_login_at']);
        });
    }
};
