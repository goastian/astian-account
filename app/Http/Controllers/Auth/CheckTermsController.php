<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\GlobalController as Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckTermsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * @return RedirectResponse
     */
    public function checkTerms(Request $request)
    {
        if (!$request->user()->accept_terms) {
            return view("auth.terms");
        }

        return redirect('/login');
    }

    /**
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function acceptTerms(Request $request)
    {
        $this->validate($request,  [
            'accept_terms' => ['required'],
        ]);
        
        if ($request->accept_terms) {
            auth()->user()->acceptTerms();
            return redirect('/');
        }

        return back();
    }
}
