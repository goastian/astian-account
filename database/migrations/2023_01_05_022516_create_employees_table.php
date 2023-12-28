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
        Schema::create('employees', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('name', 100);
            $table->string('last_name', 100);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('country', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('address', 150)->nullable();
            $table->string('phone', 15)->nullable();
            $table->date('birthday')->nullable();
            $table->date('verified_at')->nullable();
            $table->boolean('client')->default(1);
            $table->timestamps();
            $table->softDeletes(); 
            $table->primary('id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
