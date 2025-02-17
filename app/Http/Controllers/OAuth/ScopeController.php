<?php

namespace App\Http\Controllers\OAuth;

use App\Traits\Scopes;
use App\Http\Controllers\GlobalController as Controller;

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
