<?php

namespace App\Http\Controllers\Auth;
 
use Illuminate\Routing\Controller;
use Elyerr\ApiExtend\Events\LoginEvent;
use App\Http\Requests\Auth\LoginRequest;
use App\Transformers\Auth\EmployeeTransformer;
use Elyerr\ApiExtend\Events\LogoutEvent;
use Elyerr\ApiExtend\Assets\JsonResponser;

class AuthorizationController extends Controller
{
    use JsonResponser;

    public function __construct()
    {
        $this->middleware('guest')->only('store');
        $this->middleware('auth:sanctum')->only('destroy');
        $this->middleware('transform.request:'. EmployeeTransformer::class)->only('store');
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

        $token = request()->user()->createToken($request->email ."|". $_SERVER['HTTP_USER_AGENT']);

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
