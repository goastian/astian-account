<?php

namespace App\Http\Controllers\Web\OAuth;

use App\Repositories\Traits\Scopes;
use App\Http\Controllers\WebController;

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
