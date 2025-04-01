<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('vendor_master', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_name', 255);
            $table->boolean('is_active')->default(1);
            $table->string('support_email', 255)->nullable();
            $table->string('support_mobile', 20)->nullable();
            $table->string('logo')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_master');
    }
};
