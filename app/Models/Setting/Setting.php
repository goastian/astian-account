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
use Illuminate\Database\QueryException;
use App\Models\OAuth\PersonalAccessClient;

class Setting extends Master
{
    public $table = "settings";

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

        Config::set('app.name', settingItem('app.name', 'Oauth2 Server'));

        Config::set('passport.personal_access_client.id', settingItem('passport_personal_access_client_id'));
        Config::set('passport.personal_access_client.secret', settingItem('passport_personal_access_client_secret'));

        Setting::getPassportSetting();
        Setting::getRedisConfig();
        Setting::getQueueSetting();
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
        settingAdd('app.name', 'Oauth2 Server');

        //expires time for reset password
        settingAdd('auth.passwords.users.expire', 10);
        //expires time to try another request 
        settingAdd('auth.passwords.users.throttle', 10);

        //------------------------REDIS CONFIGURATION-------------------//
        //redis default settings
        settingAdd('database.redis.default.url', null);
        settingAdd('database.redis.default.host', '127.0.0.1');
        settingAdd('database.redis.default.username', null);
        settingAdd('database.redis.default.password', null);
        settingAdd('database.redis.default.port', '6379');
        settingAdd('database.redis.default.database', 0);
        //redis cache settings
        settingAdd('database.redis.cache.url', null);
        settingAdd('database.redis.cache.host', '127.0.0.1');
        settingAdd('database.redis.cache.username', null);
        settingAdd('database.redis.cache.password', null);
        settingAdd('database.redis.cache.port', '6379');
        settingAdd('database.redis.cache.database', 1);


        //---------------------QUEUES CONFIG--------------------///
        //default queues
        settingAdd('queue.default', 'database');

        //Sync setting
        settingAdd('queue.connections.sync.driver', 'sync');

        //Database settings
        settingAdd('queue.connections.database.driver', 'database');
        settingAdd('queue.connections.database.table', 'jobs');
        settingAdd('queue.connections.database.queue', 'default');
        settingAdd('queue.connections.database.retry_after', 90);
        settingAdd('queue.connections.database.after_commit', false);

        //beanstalkd Settings
        settingAdd('queue.connections.beanstalkd.driver', 'beanstalkd');
        settingAdd('queue.connections.beanstalkd.host', 'localhost');
        settingAdd('queue.connections.beanstalkd.queue', 'default');
        settingAdd('queue.connections.beanstalkd.retry_after', 90);
        settingAdd('queue.connections.beanstalkd.block_for', 0);
        settingAdd('queue.connections.beanstalkd.after_commit', false);

        //AWS settings
        settingAdd('queue.connections.sqs.driver', 'sqs');
        settingAdd('queue.connections.sqs.key', null);
        settingAdd('queue.connections.sqs.secret', null);
        settingAdd('queue.connections.sqs.prefix', 'https://sqs.us-east-1.amazonaws.com/your-account-id');
        settingAdd('queue.connections.sqs.queue', 'default');
        settingAdd('queue.connections.sqs.suffix', null);
        settingAdd('queue.connections.sqs.region', 'us-east-1');
        settingAdd('queue.connections.sqs.after_commit', false);

        //Redis Settings
        settingAdd('queue.connections.redis.driver', 'redis');
        settingAdd('queue.connections.redis.connection', 'default');
        settingAdd('queue.connections.redis.queue', 'default');
        settingAdd('queue.connections.redis.retry_after', 90);
        settingAdd('queue.connections.redis.block_for', null);
        settingAdd('queue.connections.redis.after_commit', false);

        //Fail queue settings
        settingAdd('queue.failed.driver', 'database-uuids');
        settingAdd('queue.failed.database', 'mysql');
        settingAdd('queue.failed.table', 'failed_jobs');
    }

    /**
     * Redis configuration
     * @return void
     */
    public static function getRedisConfig()
    {
        Config::set('database.redis.default.url', settingItem('database.redis.default.url', null));
        Config::set('database.redis.default.host', settingItem('database.redis.default.host', '6379'));
        Config::set('database.redis.default.username', settingItem('database.redis.default.username', null));
        Config::set('database.redis.default.password', settingItem('database.redis.default.password', null));
        Config::set('database.redis.default.port', settingItem('database.redis.default.port', '127.0.0.1'));
        Config::set('database.redis.default.database', settingItem('database.redis.default.database', 0));

        Config::set('database.redis.cache.url', settingItem('database.redis.cache.url', null));
        Config::set('database.redis.cache.host', settingItem('database.redis.cache.host', '6379'));
        Config::set('database.redis.cache.username', settingItem('database.redis.cache.username', null));
        Config::set('database.redis.cache.password', settingItem('database.redis.cache.password', null));
        Config::set('database.redis.cache.port', settingItem('database.redis.cache.port', '127.0.0.1'));
        Config::set('database.redis.cache.database', settingItem('database.redis.cache.database', 1));
    }

    /**
     * Setting for laravel passport
     * @return void
     */
    public static function getPassportSetting()
    {
        Passport::loadKeysFrom(base_path('secrets/oauth'));

        //Cookies names
        Passport::cookie(settingItem('cookie_name'));

        try {
            //Scopes
            $scopes = [];
            foreach (Scope::where('active', true)->get() as $key => $value) {
                $scopes += array($value->gsr_id => $value->role->description);
            }
            Passport::tokensCan($scopes);
        } catch (QueryException $th) {
        }

        /**
         * Custom models for laravel passport
         */
        Passport::useTokenModel(Token::class);
        Passport::useRefreshTokenModel(RefreshToken::class);
        Passport::useAuthCodeModel(AuthCode::class);
        Passport::useClientModel(Client::class);
        Passport::usePersonalAccessClientModel(PersonalAccessClient::class);
    }

    /**
     * Loading default queue settings
     * @return void
     */
    public static function getQueueSetting()
    {
        //default queues
        Config::set('queue.default', settingItem('queue.default', 'sync'));

        //Sync setting
        Config::set('queue.connections.sync.driver', settingItem('queue.connections.sync.driver', 'sync'));

        //Database settings
        Config::set('queue.connections.sync.driver', settingItem('queue.connections.database.driver', 'database'));
        Config::set('queue.connections.sync.table', settingItem('queue.connections.database.table', 'jobs'));
        Config::set('queue.connections.sync.queue', settingItem('queue.connections.database.queue', 'default'));
        Config::set('queue.connections.sync.retry_after', settingItem('queue.connections.database.retry_after', 90));
        Config::set('queue.connections.sync.after_commit', settingItem('queue.connections.database.after_commit', false));

        //beanstalkd Settings
        Config::set('queue.connections.beanstalkd.driver', settingItem('queue.connections.beanstalkd.driver', 'beanstalkd'));
        Config::set('queue.connections.beanstalkd.host', settingItem('queue.connections.beanstalkd.host', 'localhost'));
        Config::set('queue.connections.beanstalkd.queue', settingItem('queue.connections.beanstalkd.queue', 'default'));
        Config::set('queue.connections.beanstalkd.retry_after', settingItem('queue.connections.beanstalkd.retry_after', 90));
        Config::set('queue.connections.beanstalkd.block_for', settingItem('queue.connections.beanstalkd.block_for', 0));
        Config::set('queue.connections.beanstalkd.after_commit', settingItem('queue.connections.beanstalkd.after_commit', false));

        //AWS settings
        Config::set('queue.connections.sqs.driver', settingItem('queue.connections.sqs.driver', 'sqs'));
        Config::set('queue.connections.sqs.key', settingItem('queue.connections.sqs.key', null));
        Config::set('queue.connections.sqs.secret', settingItem('queue.connections.sqs.secret', null));
        Config::set('queue.connections.sqs.prefix', settingItem('queue.connections.sqs.prefix', 'https://sqs.us-east-1.amazonaws.com/your-account-id'));
        Config::set('queue.connections.sqs.queue', settingItem('queue.connections.sqs.queue', 'default'));
        Config::set('queue.connections.sqs.suffix', settingItem('queue.connections.sqs.suffix', null));
        Config::set('queue.connections.sqs.region', settingItem('queue.connections.sqs.region', 'us-east-1'));
        Config::set('queue.connections.sqs.after_commit', settingItem('queue.connections.sqs.after_commit', false));

        //Redis Settings
        Config::set('queue.connections.redis.driver', settingItem('queue.connections.redis.driver', 'redis'));
        Config::set('queue.connections.redis.connection', settingItem('queue.connections.redis.connection', 'default'));
        Config::set('queue.connections.redis.queue', settingItem('queue.connections.redis.queue', 'default'));
        Config::set('queue.connections.redis.retry_after', settingItem('queue.connections.redis.retry_after', 90));
        Config::set('queue.connections.redis.block_for', settingItem('queue.connections.redis.block_for', null));
        Config::set('queue.connections.redis.after_commit', settingItem('queue.connections.redis.after_commit', false));

        //Fail queue settings
        Config::set('queue.failed.driver', settingItem('queue.failed.driver', 'database-uuids'));
        Config::set('queue.failed.database', settingItem('queue.failed.database', 'mysql'));
        Config::set('queue.failed.table', settingItem('queue.failed.table', 'failed_jobs'));
    }
}
