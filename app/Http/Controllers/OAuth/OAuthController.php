<?php

namespace App\Http\Controllers\OAuth;

use App\Http\Controllers\Controller;
use App\Transformers\Auth\EmployeeTransformer;
use Inertia\Inertia;

class OAuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        return Inertia::render('Dashboard');
    }
}
