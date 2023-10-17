<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

    public function clientes(){
        return Inertia::render('OAuth/Clients');
    }
}
