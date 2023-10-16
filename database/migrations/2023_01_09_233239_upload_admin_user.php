<?php

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
            'admin' => "acceso total al sistema", 
            'read' => "acceso para leer informacion", 
            'write' => "acceso para registrar informacion",
            'update' => "acceso para actualizar informacion",
            'destroy' => "acceso para borrar informacion",
            'disable' => "acceso para deshabilitar un recurso",
            'enable' => "acceso para habilitar recursos",
            'download' => "acceso para descargar informacion"
        ];

        foreach ($roles as $key => $value) {
            Role::create([
                'name' => $key,
                'description' => $value
            ])->save();
        }

        Employee::create([
            'name' => 'admin',
            'last_name' => 'admin',
            'email' => 'spondylus@admin.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'document_type' => 'DNI',
            'document_number' => '00000000',
            'country' => 'peru',
            'department' => 'lima',
            'address' => 'su casa en la tierra',
            'phone' => '789526352',
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
