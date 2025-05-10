<?php

namespace App\Http\Controllers\Web\OAuth;

use App\Http\Controllers\ApiController as Controller;
use Illuminate\Support\Facades\Auth;

class CredentialsController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Revoke all tokens
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function revokeCredentials()
    {
        $tokens = Auth::user()->tokens;

        $this->removeCredentials($tokens);

        $this->privateChannel("RevokeCredentialsEvent." . request()->user()->id, "Credentials remove");

        return $this->message(__("All credentials have been revoked."));
    }
}
