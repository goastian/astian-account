<?php

return [

    'admin' => [
        'dashboard' => true,
        'groups' => true,
        'roles' => true,
        'services' => true,
        'users' => true,
        'clients' => true,
        'broadcast' => true,
        'plans' => true,
        'transactions' => true,
        'terminal' => true,
        'settings' => true,

    ],

    'users' => [
        'developers' => false,
        'api' => false,
        'clients' => false
    ],

    'guest' => [
        'register' => true
    ]

];