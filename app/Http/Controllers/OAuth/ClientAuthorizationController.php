<?php

namespace App\Http\Controllers\OAuth;

use App\Http\Controllers\GlobalController as Controller;
use App\Http\Controllers\OAuth\Scopes;
use Illuminate\Http\Request;

class ClientAuthorizationController extends Controller
{
    use Scopes;

    public function __construct()
    {
        $this->middleware('auth')->only('grant_scopes');
    }

    /**
     * show view for user select scopes
     * @param Illuminate\Http\Request $request
     * @return Illuminate\View\View
     */
    public function grant_scopes(Request $request)
    {
        $scopes = $this->scopes();

        return view('auth.grant-scopes', ['scopes' => $scopes]);
    }
}
