<?php

use App\Models\User\Role;
use Illuminate\Support\Str;
use App\Models\User\Employee;
use Illuminate\Support\Facades\DB;
use App\Models\Broadcasting\Broadcast;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * datos del user administrador
         */

        foreach (Role::rolesByDefault() as $key => $value) {
            Role::create([
                'name' => $key,
                'description' => $value,
            ])->save();
        }

        foreach (Broadcast::channelsByDefault() as $key => $value) {
            Broadcast::create([
                'channel' => $key,
                'description' => $value,
            ])->save();
        }

        DB::table('users')->insert([
            'id' => Str::uuid(),
            'name' => 'admin',
            'last_name' => 'administrador',
            'email' => 'test@admin.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Employee::first()->roles()->syncWithoutDetaching(Role::first()->id);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
