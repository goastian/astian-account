<?php

namespace App\Http\Controllers;

use App\Assets\Asset;
use App\Assets\JsonResponser; 

class GlobalController extends Controller
{
    use JsonResponser, Asset;

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
