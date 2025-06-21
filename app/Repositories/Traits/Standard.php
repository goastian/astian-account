<?php

namespace App\Repositories\Traits;

use App\Models\User\User;
use Illuminate\Support\Str;

trait Standard
{
    /**
     * Add standard to save notification in database
     * @param mixed $title
     * @param mixed $description
     * @param mixed $url
     * @return array{created_at: string, description: array|string|null, link: mixed, title: array|string|null}
     */
    public function notificationDatabase($title, $description, $url = null)
    {
        return [
            "title" => __($title),
            "description" => __($description),
            "link" => $url,
            "created_at" => date('Y-m-d H:i:s', strtotime(now()))
        ];
    }
}
