<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    /**
     * Construct of class
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

}
