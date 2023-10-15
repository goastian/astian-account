<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Transformers\Auth\EmployeeTransformer;
use Elyerr\ApiExtend\Assets\JsonResponser;
use Elyerr\ApiExtend\Events\LoginEvent;
use Elyerr\ApiExtend\Events\LogoutEvent;
use Illuminate\Routing\Controller;

class AuthorizationController extends Controller
{
    use JsonResponser;

    public function __construct()
    {
        $this->middleware('guest')->only('store');
        $this->middleware('auth:sanctum')->only('destroy');
        $this->middleware('transform.request:' . EmployeeTransformer::class)->only('store');
    }
    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        
        $abilities = $request->user()->roles()->get()->pluck('name')->implode(',');

        $token = request()->user()->createToken($_SERVER['HTTP_USER_AGENT'], [$abilities]);

        LoginEvent::dispatch(request()->user());

        return response()->json(['data' => [
            'Authorization' => "Bearer " . $token->plainTextToken,
        ]], 201);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        request()->user()->currentAccessToken()->delete();

        LogoutEvent::dispatch(request()->user());

        return $this->message('La sesion ha sido cerrada.', 200);
    }
}
