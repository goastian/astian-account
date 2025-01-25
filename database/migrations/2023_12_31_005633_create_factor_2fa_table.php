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
        Schema::create('factor_2fa', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('status')->index();
            $table->string('email')->index();
            $table->string('code');
            $table->dateTime('created_at'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('factor_2fa');
    }
};
