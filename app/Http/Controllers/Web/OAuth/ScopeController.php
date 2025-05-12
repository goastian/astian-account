<?php

namespace App\Http\Controllers\Web\OAuth;

use App\Http\Controllers\WebController;
use App\Traits\Scopes;


class ScopeController extends WebController
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
