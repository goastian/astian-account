<?php

namespace App\Http\Controllers\OAuth;

use App\Http\Controllers\GlobalController as Controller;
use Illuminate\Support\Facades\Auth;

class CredentialsController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * revoca todas las credenciales del usuario autnteicado
     * @return Json
     */
    public function revokeCredentials()
    {
        $tokens = Auth::user()->tokens;

        $this->removeCredentials($tokens);

        //send event
        $this->privateChannel("RevokeCredentialsEvent." . request()->user()->id, "Credentials remove");

        return $this->message(__("All of your credentials have been canceled.") . now());
    }
}
