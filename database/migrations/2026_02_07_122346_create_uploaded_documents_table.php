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
        Schema::create('uploaded_documents', function (Blueprint $table) {
            $table->id();
            $table->string('document_number')->unique();
            $table->string('document_title');
            $table->text('description');
            $table->string('document_type');
            $table->string('category');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_size');
            $table->string('file_type');
            $table->string('reference_number')->nullable();
            $table->text('tags')->nullable();
            $table->date('effective_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('visibility');
            $table->boolean('view_only_access')->default(false);
            $table->boolean('download_permission')->default(true);
            $table->boolean('edit_permission')->default(false);
            $table->boolean('share_permission')->default(false);
            $table->text('authorized_users')->nullable();
            $table->string('uploaded_by');
            $table->string('department');
            $table->string('status')->default('Active');
            $table->date('upload_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uploaded_documents');
    }
};
