<?php

namespace App\Repositories\Traits;

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
            "title" => $title,
            "message" => $description,
            "link" => $url,
        ];
    }
}
