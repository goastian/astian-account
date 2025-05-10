<?php
namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\WebController;
use Inertia\Inertia;

class UserHomePageController extends WebController
{
    /**
     * show 
     * @return \Inertia\Response
     */
    public function dashboard()
    {
        return Inertia::render(
            "Account/About",
        );
    }
}
