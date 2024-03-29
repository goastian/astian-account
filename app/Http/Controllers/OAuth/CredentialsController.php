<?php

namespace App\Http\Controllers\OAuth;

use App\Events\OAuth\RevokeCredentialsEvent;
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

        RevokeCredentialsEvent::dispatch($this->authenticated_user());

        return $this->message(__("Every one of your credentials has been canceled.") . now());

    } 
}
