<?php
namespace App\Http\Controllers\Web\Account;

use App\Http\Controllers\WebController;
use Inertia\Inertia;

class HomePageController extends WebController
{
    /**
     * show 
     * @return \Inertia\Response
     */
    public function dashboard()
    {
        return Inertia::render("Account/About");
    }
}
