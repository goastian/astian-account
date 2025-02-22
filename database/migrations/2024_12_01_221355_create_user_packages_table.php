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
        Schema::create('packages', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->uuid("user_id");
            $table->uuid('plan_id');
            $table->enum('status', ['pending', 'successful', 'failed', 'cancelled']);
            $table->decimal('price')->default(0);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->dateTime('trial_start_at')->nullable();
            $table->tinyInteger('trial_duration_days')->nullable();
            $table->dateTime('cancellation_date')->nullable();
            $table->dateTime('last_renewal_at')->nullable();
            $table->dateTime('next_payment_due')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->json('meta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};
