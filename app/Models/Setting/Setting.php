<?php

namespace App\Models\Setting;

use App\Models\Master; 

class Setting extends Master
{
    public $table = "settings";

    protected $fillable = [
        'key',
        'value',
        'user_id'
    ];
}
