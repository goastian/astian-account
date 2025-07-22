<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('name')->index();
            $table->string('slug')->index();
            $table->longText('description'); 
            $table->boolean('active')->default(false);
            $table->boolean('bonus_enabled')->default(false);
            $table->tinyInteger('bonus_duration', false, true)->default(0);
            $table->boolean('trial_enabled')->default(false);
            $table->tinyInteger('trial_duration', false, true)->default(0);
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
        Schema::dropIfExists('plans');
    }
};
