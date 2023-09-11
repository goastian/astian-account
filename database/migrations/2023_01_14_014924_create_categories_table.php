<?php

use App\Enum\EnumType;
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
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('name', 100);
            $table->float('price',8,2);
            $table->tinyInteger('capacity');
            $table->enum('air_conditionar', EnumType::yes_or_not());
            $table->enum('tv', EnumType::yes_or_not());
            $table->enum('bathroom', EnumType::yes_or_not());
            $table->enum('hot_water', EnumType::yes_or_not());
            $table->enum('cold_water', EnumType::yes_or_not());
            $table->enum('wifi', EnumType::yes_or_not());
            $table->enum('fan', EnumType::yes_or_not());
            $table->softDeletes();
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
        Schema::dropIfExists('categories');
    }
};
