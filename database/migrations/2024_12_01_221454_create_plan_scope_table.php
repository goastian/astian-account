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
        Schema::create('plan_scope', function (Blueprint $table) {
            $table->uuid('scope_id');
            $table->uuid('plan_id');

            $table->unique(['scope_id', 'plan_id']);
            $table->foreign('scope_id')->references('id')->on('scopes')->onDelete('RESTRICT');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_scope');
    }
};
