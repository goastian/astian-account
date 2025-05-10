<?php
namespace App\Http\Controllers\Web\Auth;
 

use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthorizationModuloController extends WebController
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Handle redirection to the module
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirectModule(Request $request)
    {
        $redirect_to = $request->redirect_to;

        if ($redirect_to) {
            return view('auth.redirect-module', ['redirect_to' => $redirect_to]);
        }

        return redirect()->back();
    }
}
