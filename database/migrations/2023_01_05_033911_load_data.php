<?php

use App\Models\User\Employee;
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
