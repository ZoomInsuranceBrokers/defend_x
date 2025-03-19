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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id'); // Foreign key to roles table
            $table->foreignId('company_id'); // Foreign key to companies table
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('full_name')->nullable(); // Full name can be generated later, but keeping it as nullable
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('photo')->nullable(); // For profile photo URL or path
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('mobile', 15)->nullable(); // Mobile number
            $table->date('dob')->nullable(); // Date of birth
            $table->timestamp('created_on')->useCurrent(); // When the record is created
            $table->unsignedBigInteger('created_by')->nullable(); // Foreign key for the user who created this record
            $table->boolean('is_active')->default(true); // User active status
            $table->boolean('is_delete')->default(false); // Soft delete flag
            $table->rememberToken();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
