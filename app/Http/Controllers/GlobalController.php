<?php

namespace App\Http\Controllers;

use App\Assets\JsonResponser;
use Illuminate\Http\Request;

class GlobalController extends Controller
{
    use JsonResponser;
    
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }


    public function AuthKey()
    {
       return request()->user()->id;
    }
}
