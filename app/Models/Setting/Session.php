<?php
namespace App\Models\Setting;

use App\Models\Master;
use App\Transformers\Session\SessionTransformer;
use Elyerr\ApiResponse\Assets\Asset;

class Session extends Master
{
    use Asset;

    public $table = "sessions";

    public $transformer = SessionTransformer::class;

    protected $fillable = [
        //
    ];

    protected $hidden = [
        //  'user_id'
    ];

    public function getLastActivityAttribute($value)
    {
        $date = date('Y-m-d H:i:s', $value);

        return $this->format_date($date);
    }
}
