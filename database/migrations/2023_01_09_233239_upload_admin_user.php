<?php

use App\Models\Broadcasting\Broadcast;
use App\Models\User\Employee;
use App\Models\User\Role;
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
                'description' => $value
            ])->save();
        }

        Employee::create([
            'name' => 'admin',
            'last_name' => 'administrador',
            'email' => 'test@admin.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',//password
        ])->save();

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
