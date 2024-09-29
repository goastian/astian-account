<?php

namespace App\Models\OAuth;

use Elyerr\ApiResponse\Assets\Asset;
use Elyerr\ApiResponse\Assets\Timestamps;
use Laravel\Passport\Token as PassportToken;

class Token extends PassportToken
{
    use Timestamps, Asset;

    public function getCreatedAtAttribute($value)
    {
        $date = date('Y-m-d H:i:s', strtotime($value));

        return $this->format_date($date);
    }

    public function getUpdatedAtAttribute($value)
    {
        $date = date('Y-m-d H:i:s', strtotime($value));

        return $this->format_date($date);
    }

    public function getExpiresAtAttribute($value)
    {
        $date = date('Y-m-d H:i:s', strtotime($value));

        return $this->format_date($date);
    }
}
