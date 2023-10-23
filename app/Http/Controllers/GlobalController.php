<?php

namespace App\Http\Controllers;

use App\Transformers\Auth\EmployeeTransformer;
use Elyerr\ApiResponse\Assets\Asset;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class GlobalController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, JsonResponser, Asset;

    public $can_update = array();

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('scope:admin,read')->only('index', 'show');
        $this->middleware('scope:admin,write')->only('store');
        $this->middleware('scope:admin,update')->only('update');
        $this->middleware('scope:admin,destroy')->only('destroy');
        $this->middleware('scope:admin,disable')->only('disable');
        $this->middleware('scope:admin,enable')->only('enable');
    }

    public function authenticated_user()
    {
        return factral(Auth::user(), EmployeeTransformer::class);
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
