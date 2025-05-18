<?php
namespace App\Http\Controllers\Web\Home;

use App\Http\Controllers\WebController;
use Inertia\Inertia;


class HomeController extends WebController
{

    public function __construct()
    {

    }

    /**
     * Summary of home_page
     * @return \Inertia\Response
     */
    public function homePage()
    {
        return Inertia::render('Home/Home');
    }
}
