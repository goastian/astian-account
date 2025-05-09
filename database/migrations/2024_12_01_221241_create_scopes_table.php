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
        Schema::create('scopes', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->uuid('service_id');
            $table->uuid('role_id');
            $table->boolean('public')->default(false);
            $table->boolean('active')->default(false);
            $table->boolean('api_key')->default(false);
            $table->timestamps();

            $table->unique(['service_id', 'role_id']);
            $table->foreign('service_id')->references('id')->on('services')->onDelete('RESTRICT');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scopes');
    }
};
