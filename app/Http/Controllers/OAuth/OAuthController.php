<?php

namespace App\Http\Controllers\OAuth;

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
        return Inertia::render('OAuth/Personal/Index');
    }

    public function clientes()
    {
        return Inertia::render('OAuth/Clients/Index');
    }

    public function tokens()
    {
        return Inertia::render('OAuth/Tokens/Index');
    }

}
