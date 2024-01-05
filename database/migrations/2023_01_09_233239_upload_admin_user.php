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
        $roles = [
            "admin" => "acceso total al sistema", 
            "client" => "acceso defecto para el cliente", 
            "broadcast" => "administrar canales",
            "account" => "administrar cuentas de usuario",
            "account_read" => "acceso a ver informacion de los usuarios",
            "account_register" => "registra un nuevo usuario",
            "account_update" => "actualiza informacion de usaurios",
            "account_enable" => "permite habilitar usuarios",
            "account_disable" => "permite deshabilitar usuarios",
            "scopes" => "administrar scopes",
            "scopes_read" => "permite ver todos los scopes",
            "scopes_register" => "permite registrar nuevos scopes",
            "scopes_update" => "permite actualizar nuevos scopes",
            "scopes_destroy" => "Permite eliminar un scope",
        ];

        foreach ($roles as $key => $value) {
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
