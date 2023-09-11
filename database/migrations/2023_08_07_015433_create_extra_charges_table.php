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
        Schema::create('extra_charges', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('charge');
            $table->tinyInteger('amount', false, true)->nullable();
            $table->float('price', 8, 2);
            $table->string('extra_chargeable_id');
            $table->string('extra_chargeable_type');
            $table->timestamps();
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
        Schema::dropIfExists('extra_charges');
    }
};
