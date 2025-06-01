<?php

namespace App\Http\Controllers;

use App\Traits\Standard;
use Elyerr\ApiResponse\Assets\Asset;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\TokenRepository;
use Illuminate\Database\Eloquent\Builder;
use App\Transformers\User\AuthTransformer;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Laravel\Passport\RefreshTokenRepository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, Standard, DispatchesJobs, ValidatesRequests, JsonResponser, Asset;

    /**
     * Order by collection using params order_by and order_type
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param mixed $transformer
     * @return Builder
     */
    public function orderByBuilder(Builder $builder, $transformer = null)
    {
        $order_by = request()->order_by;
        $order_type = request()->order_type ?? 'desc';

        if (!in_array(strtolower($order_type), ['asc', 'desc'])) {
            $order_type = 'asc';
        }

        if ($transformer) {
            if (method_exists($transformer, 'getOriginalAttributes') && $order_by) {
                $order_by = $transformer::getOriginalAttributes($order_by);
            }
        } else {
            $columns = $builder->getQuery()->getConnection()->getSchemaBuilder()->getColumnListing($builder->getQuery()->from);

            if (!in_array($order_by, $columns)) {
                $order_by = null;
            }
        }

        if ($order_by) {
            $builder->orderBy($order_by, $order_type);
        } else {
            $builder->orderBy('id', $order_type);
        }

        return $builder;
    }


    /**
     * Return information about the current users and transform date in the process
     * @return mixed
     */
    public function authenticated_user()
    {
        $user = fractal(Auth::user(), AuthTransformer::class);

        return json_decode(json_encode($user))->data;
    }


    /**
     * Remove the all credential 
     * @param mixed $tokens
     * @return void
     */
    public function removeCredentials($tokens)
    {
        $tokenRepository = app(TokenRepository::class);
        $refreshTokenRepository = app(RefreshTokenRepository::class);

        foreach ($tokens as $token) {

            $tokenRepository->revokeAccessToken($token->id);

            $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);
        }
    }
}
