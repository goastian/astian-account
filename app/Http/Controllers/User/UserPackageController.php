<?php
namespace App\Http\Controllers\User;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Models\Subscription\Package;
use App\Http\Controllers\GlobalController;
use App\Transformers\User\UserPackageTransformer;

class UserPackageController extends GlobalController
{

    public function index(User $user, Package $package)
    {
        $data = $package->query();

        $data = $data->where('user_id', auth()->user()->id);

        $params = $this->filter_transform(UserPackageTransformer::class);

        $data = $this->searchByBuilder($data, $params);

        $data = $this->orderByBuilder($data, UserPackageTransformer::class);

        return $this->showAllByBuilder($data, UserPackageTransformer::class);
    }

    /**
     * show resource
     * @param \App\Models\Subscription\Package $package
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Package $package)
    {
        return $this->showOne($package, UserPackageTransformer::class);
    }
}
