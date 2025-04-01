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
        Schema::create('company_master', function (Blueprint $table) {
            $table->increments('comp_id');
            $table->string('comp_name', 100)->nullable()->index();
            $table->string('file_dir', 255)->nullable()->index();
            $table->string('business_type', 255)->nullable();
            $table->string('comp_addr', 200)->nullable()->index();
            $table->string('comp_city', 45)->nullable()->index();
            $table->string('comp_state', 45)->nullable()->index();
            $table->string('comp_pincode', 45)->nullable()->index();
            $table->string('comp_icon_url', 255)->nullable()->index();
            $table->integer('status')->default(1)->index();
            $table->integer('is_approved')->default(0)->index();
            $table->integer('updated_by')->nullable()->index();
            $table->integer('created_by')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_master');
    }
};
