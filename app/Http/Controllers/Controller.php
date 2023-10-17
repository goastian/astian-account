<?php

namespace App\Http\Controllers;

use Elyerr\ApiResponse\Assets\Asset;
use Elyerr\ApiResponse\Assets\Timestamps;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, JsonResponser, Asset,Timestamps;

}
