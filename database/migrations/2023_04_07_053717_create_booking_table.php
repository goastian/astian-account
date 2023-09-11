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
        Schema::create('booking', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('code')->unique()->nullable();
            $table->dateTime('check_in');
            $table->dateTime('check_out');
            $table->string('client_id')->nullable();
            $table->string('company_id')->nullable();
            $table->enum('type', EnumType::booking_type());
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
        Schema::dropIfExists('booking');
    }
};
