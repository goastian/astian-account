<?php

namespace App\Models;

use Elyerr\ApiExtend\Assets\Asset;
use Elyerr\ApiExtend\Assets\Timestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Master extends Model
{
    use HasUuids,Timestamps, HasFactory, Asset; 

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
