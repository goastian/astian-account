<?php

namespace App\Models\Setting;

use App\Models\Master;
use App\Models\OAuth\Token;
use App\Models\OAuth\Client;
use App\Models\OAuth\AuthCode;
use Laravel\Passport\Passport;
use App\Models\OAuth\RefreshToken;
use App\Models\Subscription\Scope;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use App\Models\OAuth\PersonalAccessClient;

class Setting extends Master
{
    protected $table = "settings";

    protected $fillable = [
        'key',
        'value',
        'user_id'
    ];

    /**
     * Set default values 
     * @return void
     */
    public static function getDefaultSetting()
    {
        if ($item = settingItem('schema_mode', 'https')) {
            URL::forceScheme($item);
        }

        Config::set('app.name', settingItem('app_name', 'Oauth2 Server'));

        Config::set('passport.personal_access_client.id', settingItem('passport_personal_access_client_id'));
        Config::set('passport.personal_access_client.secret', settingItem('passport_personal_access_client_secret'));

        Setting::passportSetting();
    }

    /**
     * Add default setting into the system
     * @return void
     */
    public static function setDefaultKeys()
    {
        //default schema HTTP or HTTPS
        settingAdd('schema_mode', 'https');

        //Redirect page after login
        settingAdd('redirect_to', '/about');

        //Home page
        settingAdd('home_page', '/');

        //Name of cookie of laravel passport and session
        settingAdd('cookie_name', 'oauth2_server');

        //Name of cookie of laravel passport from microservices
        settingAdd('passport_token_services', 'server_authorization');

        //Expires time to check verification account in minutes
        settingAdd('verify_account_time', 5);

        //Disable option to create user by command
        settingAdd('disable_create_user_by_command', false);

        //Destroy users after 30 days
        settingAdd('destroy_user_after', 30);

        //Code 2FA time expires in minutes
        settingAdd('code_2fa_email_expires', 5);

        //Enable or disable login
        settingAdd('enable_register_member', true);

        //App name
        settingAdd('app_name', 'Oauth2 Server');

    }

    /**
     * Setting for laravel passport
     * @return void
     */
    public static function passportSetting()
    {
        Passport::loadKeysFrom(base_path('secrets/oauth'));

        //Cookies names
        Passport::cookie(settingItem('cookie_name'));

        //Scopes
        $scopes = [];
        foreach (Scope::where('active', true)->get() as $key => $value) {
            $scopes += array($value->gsr_id => $value->role->description);
        }
        Passport::tokensCan($scopes);

        /**
         * Custom models for laravel passport
         */
        Passport::useTokenModel(Token::class);
        Passport::useRefreshTokenModel(RefreshToken::class);
        Passport::useAuthCodeModel(AuthCode::class);
        Passport::useClientModel(Client::class);
        Passport::usePersonalAccessClientModel(PersonalAccessClient::class);
    }
}
