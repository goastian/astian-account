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
        Schema::dropIfExists('transactions');
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('currency');
            $table->string('status'); 
            $table->unsignedBigInteger('subtotal')->nullable();
            $table->unsignedBigInteger('total')->nullable();
            $table->string('payment_method');
            $table->string('payment_method_id')->nullable();
            $table->string('billing_period');
            $table->boolean('renew')->default(false);
            $table->string('session_id')->nullable();
            $table->string('payment_intent_id')->nullable();
            $table->text('payment_url')->nullable();
            $table->json('response')->nullable(); //save response
            $table->json('meta')->nullable(); //save package
            $table->string('code');
            $table->uuid('package_id');
            $table->uuid('user_id')->nullable();
            $table->timestamps();

            $table->foreign('package_id')->references('id')->on('packages')->onDelete('RESTRICT');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
