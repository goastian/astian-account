<?php

namespace App\Traits;

use App\Models\User\User;
use Illuminate\Support\Str;

trait Standard
{
    /**
     * Add standard to save notification in database
     * @param mixed $title
     * @param mixed $description
     * @return array
     */
    public function notificationDatabase($title, $description)
    {
        return [
            "title" => __($title),
            "description" => __($description),
            "created_at" => date('Y-m-d H:i:s', strtotime(now()))
        ];
    }
}
