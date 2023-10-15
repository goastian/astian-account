<?php

namespace App\Http\Controllers;

use Elyerr\ApiExtend\Assets\Asset;
use Elyerr\ApiExtend\Assets\JsonResponser;
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
        $this->middleware('auth:sanctum');
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
