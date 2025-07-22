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
            $table->string("name_en")->index();
            $table->string("name_es")->index();
            $table->string("continent_en");
            $table->string("continent_es");
            $table->string("capital_en");
            $table->string("capital_es");
            $table->string("dial_code")->index();
            $table->string("code_2")->index();
            $table->string("code_3")->index();
            $table->string("tld")->index();
            $table->string("km2")->index();
            $table->string("emoji")->index();
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
