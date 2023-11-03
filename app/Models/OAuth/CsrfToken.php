<?php

namespace App\Models\OAuth;

use App\Models\Master;
use DateInterval;
use DateTime;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Exception;
use Illuminate\Support\Str;

class CsrfToken extends Master
{
    public $table = "csrf_tokens";

    public $timestamps = false;

    protected $seconds = "PT10S";

    protected $fillable = [
        'client_id',
        'token',
    ];

    /**
     * genera un token temporal que sera valido por un tiempo determinado
     * @param mixed $id
     * @return \App\Models\OAuth\CsrfToken
     */
    public static function generateToken($id)
    {
        if (!isset($id)) {
            throw new ReportError("Credenciales no encontrada", 400);
        }
        $token = CsrfToken::create([
            'token' => Str::random(40),
            'client_id' => $id,
        ]);

        return $token;
    }

    /**
     * Encuentra un token csrf de un cliente  y lo elimina luego de verificar su autenticidad
     * @param String $token
     * @param String|Int $id
     * @return Bool
     */
    public static function findToken($token, $id, $grant_type = 'authorization_code')
    {
        if (!isset($token) || !isset($id)) {
            throw new ReportError("credenciales no encontradas", 400);
        }

        try {

            $CsrfToken = CsrfToken::where('token', $token)->first();

            if ($CsrfToken->client_id != $id) { //verify user
                throw new ReportError("Recurso no se encontro", 400);
            }

            $CsrfToken->delete(); //remove token

            return ($grant_type == "authorization_code") ?
            ($CsrfToken->tokenIsValid() ?: false): true;

        } catch (Exception $e) {
            throw new ReportError("Recurso no se encontro", 400);
        }
    }

    /**
     * verifica si un token generado aun es valido en un rago de 10 segundos
     * desde su creacion
     * @return Bool
     */
    public function tokenIsValid()
    {
        $createdAt = new DateTime($this->created_at);
        $now = now();
        $validUntil = $createdAt->add(new DateInterval($this->seconds));

        return $now <= $validUntil;

    }
}
