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
        $package = $package->query();
        $package->where('user_id', auth()->user()->id);
        $package->where("status", config('billing.status.successful.name'));
        $packages = $package->latest('created_at')->take(3)->get();

        return Inertia::render(
            "Account/About",
            [
                'transactions' => fractal($packages, UserPackageTransformer::class)->toArray()['data'],
            ]
        );
    }
}
