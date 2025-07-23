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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('name', 100)->index();
            $table->string('last_name', 100)->index();
            $table->string('email', 100)->unique()->index();
            $table->string('password');
            $table->string('country', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('address', 150)->nullable();
            $table->string('dial_code')->nullable();
            $table->string('phone', 15)->nullable()->index();
            $table->date('birthday')->nullable();
            $table->datetime('verified_at')->nullable();
            $table->boolean('m2fa')->default(false);
            $table->boolean('totp')->default(false);
            $table->boolean("accept_terms")->nullable();
            $table->boolean('accept_cookies')->nullable();
            $table->dateTime('last_connected')->nullable();
            $table->uuid('partner_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
