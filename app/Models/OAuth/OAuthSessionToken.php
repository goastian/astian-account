<?php

namespace App\Models\OAuth;

use App\Models\Master;
use App\Models\Setting\Session;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OAuthSessionToken extends Master
{
    use HasFactory;

    /**
     * Table name
     * @var string
     */
    public $table = "oauth_session_tokens";

    protected $fillable = [
        'session_id',
        'oauth_access_token_id',
        'oauth_auth_code_id'
    ];


    /**
     * Retrieve the session id
     */
    public function getSessionId()
    {
        return $this->session_id;
    }

    /**
     * Retrieve the access token id
     */
    public function getAccessTokenId()
    {
        return $this->oauth_access_token_id;
    }

    /**
     * Retrieve the code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Belong to the session
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Session, OAuthSessionToken>
     */
    public function session()
    {
        return $this->belongsTo(Session::class);
    }


    public function token()
    {
        return $this->hasOne(Token::class, 'id', 'oauth_access_token_id');
    }
}
