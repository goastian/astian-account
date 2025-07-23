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
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('name')->index();
            $table->string('slug')->index();
            $table->string('description')->nullable();
            $table->string('visibility')->default('private');
            $table->boolean('system')->default(false);
            $table->uuid('group_id');
            $table->timestamps();

            $table->unique(['slug', 'group_id']);
            $table->foreign('group_id')
                ->references('id')
                ->on('groups')
                ->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
};
