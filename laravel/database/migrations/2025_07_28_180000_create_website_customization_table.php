<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('website_customization', function (Blueprint $table) {
            $table->id();
            $table->string('setting_name', 100)->unique();
            $table->text('setting_value');
            $table->enum('setting_type', ['color', 'text', 'number', 'boolean', 'file'])->default('text');
            $table->string('category', 50)->default('general');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('website_customization');
    }
};