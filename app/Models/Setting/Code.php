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
    protected $table = "factor_2fa";

    public $timestamps = false;

    protected $fillable = [
        'status',
        'email',
        'code',
        'created_at',
    ];

    /**
     * Destroy token
     * @param mixed $status
     * @return void
     */
    public static function destroyToken($status)
    {
        Code::where('status', $status)->delete();
    }
}
