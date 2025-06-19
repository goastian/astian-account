<?php
namespace App\Http\Controllers\Web\Account;

use Inertia\Inertia;
use App\Models\Subscription\Package;
use App\Http\Controllers\WebController;
use App\Transformers\User\UserPackageTransformer;

class HomePageController extends WebController
{
    /**
     * show 
     * @return \Inertia\Response
     */
    public function dashboard(Package $package)
    {
        return Inertia::render("Account/About");
    }
}
