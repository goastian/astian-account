<?php

namespace App\Models\User;

use App\Models\Master as master;
use App\Transformers\Role\RoleTransformer;

class Role extends master
{

    public $table = "roles";

    public $view = "roles";

    public $transformer = RoleTransformer::class;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
    ];

    public function users()
    {
        return $this->belongsToMany(Employee::class, 'employee_role');
    }

    public static function default()
    {
        return [
            "admin" => "acceso total al sistema",
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
    }
}
