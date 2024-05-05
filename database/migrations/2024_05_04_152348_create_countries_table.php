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
        Schema::create('countries', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string("name_en");
            $table->string("name_es");
            $table->string("continent_en");
            $table->string("continent_es");
            $table->string("capital_en");
            $table->string("capital_es");
            $table->string("dial_code");
            $table->string("code_2");
            $table->string("code_3");  
            $table->string("tld");  
            $table->string("km2");  
            $table->string("emoji");  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
};
