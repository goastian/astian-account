<?php

namespace App\Http\Controllers\Auth;

use App\Assets\Device;
use App\Events\Auth\LoginEvent;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Controllers\GlobalController as Controller;
use App\Transformers\Auth\EmployeeTransformer;

class AuthorizationController extends Controller
{
    use  Device;

    public function __construct()
    {
        $this->middleware('guest')->only('store');
        $this->middleware('auth:sanctum')->only('destroy');
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

        $token = request()->user()->createToken($this->setTokenName($request));

        LoginEvent::dispatch($this->AuthKey());

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

        return response()->json(['data' => [
            'message' => 'La sesion ha sido cerrada.'
        ]], 200);
    }
}
