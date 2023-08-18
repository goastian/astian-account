<?php

namespace App\Assets;

use DateTimeInterface;

trait Timestamps
{
 
    /**
     * Prepare a date for array / JSON serialization.
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i');
    }
}
