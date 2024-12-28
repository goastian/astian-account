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
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('slug')->nullable();
            $table->string('name', 60)->index();
            $table->boolean('system')->default(false);
            $table->string('description', 200); 
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
