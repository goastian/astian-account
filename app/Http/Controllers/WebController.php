<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class WebController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
}
