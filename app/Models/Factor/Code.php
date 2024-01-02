<?php

namespace App\Models\Factor;

use App\Models\Master;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Code extends Master
{
    use HasFactory;

    public $table = "2fa";

    //public $view = "";

    //public $transformer = "";

    public $timestamps = false;

    protected $fillable = [
        'status',
        'email',
        'code',
        'created_at',
    ];

    /**
     * destroy tokens
     *
     * @param String $status
     */
    public static function destroyToken($status)
    {
        DB::table('2fa')->where('status', $status)->delete();
    }
}
