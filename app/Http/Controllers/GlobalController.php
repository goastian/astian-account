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
        $this->middleware('ability:admin,read')->only('index', 'show');
        $this->middleware('ability:admin,write')->only('store');
        $this->middleware('ability:admin,update')->only('update');
        $this->middleware('ability:admin,enable')->only('enable');
        $this->middleware('ability:admin,disable')->only('disable');
        $this->middleware('ability:admin,destroy')->only('destroy');
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
