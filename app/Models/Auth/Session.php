<?php

namespace App\Models\Auth;

use App\Models\Master;
use App\Models\User\Employee;
use App\Transformers\Session\SessionTransformer;
use Illuminate\Database\Eloquent\Model;

class Session extends Master
{
    public $table = "sessions";

    public $view = "";

    public $transformer = SessionTransformer::class;

    protected $fillable = [
        //
    ];

    protected $hidden = [
        //  'user_id'
    ];

    public function getLastActivityAttribute($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

}
