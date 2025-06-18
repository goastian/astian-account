<?php

namespace App\Http\Controllers;
 
use Elyerr\ApiResponse\Assets\Asset;
use App\Repositories\Traits\Standard;
use Illuminate\Support\Facades\Auth; 
use App\Transformers\User\AuthTransformer;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests,
        Standard,
        DispatchesJobs,
        ValidatesRequests,
        JsonResponser,
        Asset;

    /**
     * Return information about the current users and transform date in the process
     * @return mixed
     */
    public function authenticated_user()
    {
        $user = fractal(Auth::user(), AuthTransformer::class);

        return json_decode(json_encode($user))->data;
    }
}
