<?php

namespace App\Http\Controllers\OAuth;

use App\Http\Controllers\GlobalController as Controller;
use App\Http\Controllers\OAuth\Scopes;

//use Laravel\Passport\Http\Controllers\ScopeController as ControllersScopeController;

class ScopeController extends Controller
{
    use Scopes;
    /**
     * Get all of the available scopes for the application.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->showAll($this->scopes(), null, 200, false);
    }
}
