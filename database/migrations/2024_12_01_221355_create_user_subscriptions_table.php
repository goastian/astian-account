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
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->uuid("user_id");
            $table->string('target_type');
            $table->uuid('target_id');
            $table->decimal('price_plan')->default(0);
            $table->decimal('price_scope')->default(0);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->dateTime('trial_start_at')->nullable();
            $table->tinyInteger('trial_duration_days')->nullable();
            $table->dateTime('cancellation_date')->nullable();
            $table->dateTime('last_renewal_at')->nullable();
            $table->dateTime('next_payment_due')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->enum('status', ['active','cancelled','expired'])->nullable();
            $table->boolean('system')->default('false');
            $table->uuid('created_by')->nullable();;
            $table->uuid('updated_by')->nullable();
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
