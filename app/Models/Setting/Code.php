<?php
namespace App\Models\Setting;

use App\Models\Master;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Code extends Master
{
    use HasFactory;

    /**
     * table name
     * @var string
     */
    public $table = "factor_2fa";

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
