<?php

namespace App\Http\Controllers;

use Elyerr\ApiResponse\Assets\Asset;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class GlobalController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, JsonResponser, Asset;

    public $can_update = array();

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function AuthKey()
    {
        return request()->user()->id;
    }

    public function lowercase($value)
    {
        return strtolower($value);
    }

    public function uppercase($value)
    {
        return strtoupper($value);
    }
}
