<?php

namespace App\Http\Controllers\OAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

    public function clientes()
    {
        return Inertia::render('OAuth/Clients/Index');
    }

    public function tokens()
    {
        return Inertia::render('OAuth/Tokens/Index');
    }

    public function sessionState(Request $request)
    {
        if (!$request->wantsJson()) {
            return redirect()->route('dashboard');
        }
        $request->session()->put('state', $state = Str::random(40));

        return $state;
    }
}
