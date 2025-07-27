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
        Schema::table('oauth_clients', function (Blueprint $table) {

            if (Schema::hasColumn('oauth_clients', 'user_id')) {
                $table->renameColumn('user_id', 'owner_id');
            }

            if (!Schema::hasColumn('oauth_clients', 'owner_type')) {
                $table->string('owner_type')->nullable()->after('owner_id');
            }

            if (Schema::hasColumn('oauth_clients', 'redirect')) {
                $table->renameColumn('redirect', 'redirect_uris');
            }

            if (!Schema::hasColumn('oauth_clients', 'grant_types')) {
                $table->text('grant_types')->nullable()->after('redirect_uris');
            }


            if (Schema::hasColumn('oauth_clients', 'personal_access_client')) {
                $table->dropColumn('personal_access_client');
            }
            if (Schema::hasColumn('oauth_clients', 'password_client')) {
                $table->dropColumn('password_client');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oauth_clients', function (Blueprint $table) {
            $table->renameColumn('owner_id', 'user_id');
            $table->dropColumn(['owner_type', 'grant_types']);
            $table->renameColumn('redirect_uris', 'redirect');
            $table->boolean('personal_access_client')->default(false);
            $table->boolean('password_client')->default(false);
        });
    }
};
