<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_scope', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('scope_id');
            $table->uuid('user_id');
            $table->uuid('package_id')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->timestamps();

            $table->foreign('scope_id')->references('id')->on('scopes')->onDelete('RESTRICT');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_scope');
    }
};
