<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User\Employee;
use Illuminate\Support\Facades\DB;

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
        $roles = ['admin', 'escritura', 'lectura'];

        foreach ($roles as $key) {
            DB::table('roles')->insert([
                'name' => $key
            ]);
        }

        Employee::create([
            'name' => 'admin',
            'last_name' => 'admin',
            'email' => 'spondylus@admin.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'document_type' => 'DNI',
            'document_number' => '00000000',
            'country' => 'peru',
            'department' => 'tumbes',
            'address' => 'Av. Tacna Nro 327 - Tumbes',
        ])->save();

        DB::table('employee_role')->insert([
            'employee_id' => 1,
            'role_id' => 1
        ]);
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
