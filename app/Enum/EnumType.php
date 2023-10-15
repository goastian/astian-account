<?php

namespace App\Enum;

class EnumType
{
    /**
     * Establece valores entre 0 y 1
     */
    public static function yes_or_not()
    {
        return [
            'si',
            'no',
        ];
    }
 

    public static function documento_type()
    {
        return explode(',', env('DOCUMENT_TYPE'));
    }
}
