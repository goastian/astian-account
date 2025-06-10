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
        Schema::create('oauth_session_tokens', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('session_id')->index();
            $table->text('oauth_auth_code_id')->index();
            $table->string('oauth_access_token_id')->index()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oauth_session_tokens');
    }
};
