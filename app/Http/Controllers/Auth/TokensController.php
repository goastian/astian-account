<?php

namespace App\Http\Controllers\Auth;

use Error;
use Illuminate\Http\Request; 
use App\Http\Controllers\GlobalController;
use App\Transformers\Tokens\TokensTransformer;
use Elyerr\ApiExtend\Events\StoreTokenEvent;
use Elyerr\ApiExtend\Exceptions\ReportError;
use Elyerr\ApiExtend\Events\DestroyTokenEvent;
use Elyerr\ApiExtend\Events\DestroyAllTokenEvent;

class TokensController extends GlobalController
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Muestra una lista de tokens pertenecientes al usuario autenticado
     *
     * @param \Illuminate\Http\Request $request
     * @return Json
     */
    public function index(Request $request)
    {
        $tokens = $request->user()->tokens;

        return $this->showAll($tokens, TokensTransformer::class, 200);
    }

    /**
     * Crea un nuevo TOken
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Json
     */
    public function store(Request $request)
    {
        $token = $request->user()->createToken($request->user()->email . "|" . $_SERVER['HTTP_USER_AGENT']);

        StoreTokenEvent::dispatch(request()->user());

        return response()->json(['token' => 'Bearer ' . $token->plainTextToken], 201);
    }

    /**
     * @param \Illiminate\Http\Request $request
     * @return Json
     */
    public function destroyAllTokens(Request $request)
    {
        $request->user()->tokens()->delete();

        DestroyAllTokenEvent::dispatch(request()->user());

        return $this->message('Los Tokens fueron revocados.', 200);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Integer $id
     * @return Json
     */
    public function destroy(Request $request, $id)
    {
        try {

            $token = $request->user()->tokens()->where('id', $id)->first();

            $token->delete();

            DestroyTokenEvent::dispatch(request()->user());

            return $this->message('El token ha sido revocado.', 201);

        } catch (Error $e) {

            throw new ReportError("Error al procesar la petición", 404);
        }
    }
}
