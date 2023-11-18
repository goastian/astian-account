<?php

namespace App\Enum;

class EnumType
{

    public static function documento_type()
    {
        return collect(explode(',', env('DOCUMENT_TYPE')))->map(function($type){
            return strtoupper($type);
        });
    }
}
