<?php

namespace App\Http\Controllers\Auth;
 
use Error;
use Laravel\Passport\Token;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\Tokens\TokensTransformer;
use Elyerr\ApiResponse\Events\StoreTokenEvent;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Elyerr\ApiResponse\Events\DestroyTokenEvent;
use Elyerr\ApiResponse\Events\DestroyAllTokenEvent;

class TokensController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
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
        $this->validate($request, [
            'scopes' => ['required', 'array', 'exists:roles,name'],
        ]);

        $token = $request->user()->createToken($_SERVER['HTTP_USER_AGENT'], $request->scopes)->accessToken;

        StoreTokenEvent::dispatch(request()->user());

        return response()->json(['token' => $token], 201);
    }

    /**
     * @param \Illiminate\Http\Request $request
     * @return Json
     */
    public function destroyAllTokens(Request $request)
    {
        $user = $request->user();

        $user->tokens->each(function (Token $token, $key) {
            $token->revoke();
        });

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

            $token = Token::find($id);

            $token->revoke();

            DestroyTokenEvent::dispatch();

            return $this->message('El token ha sido revocado.', 201);

        } catch (Error $e) {

            throw new ReportError("Error al procesar la petición", 404);
        }
    }
}
