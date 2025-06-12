<?php

namespace App\Models\Setting;

use App\Models\Master;
use App\Models\OAuth\Token;
use Illuminate\Support\Str;
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
        if ($item = config('system.schema_mode', 'https')) {
            URL::forceScheme($item);
        }

        Config::set('app.name', settingItem('app.name', 'Oauth2 Server'));
        Config::set('app.org_name', settingItem('app.org_name', 'Oauth2 org'));

        Config::set('passport.personal_access_client.id', settingItem('passport_personal_access_client_id'));
        Config::set('passport.personal_access_client.secret', settingItem('passport_personal_access_client_secret'));

        Setting::getPassportSetting();
        Setting::getRedisConfig();
        Setting::getQueueSetting();
        Setting::getFileSystems();
        Setting::getEmailSettings();
        Setting::getServicesSettings();
        Setting::getPaymentSettings();
        Setting::getSystemSetting();
        Setting::getSessionSettings();
        Setting::getCacheSettings();
        Setting::getRoutesSettings();
    }

    /**
     * Add default setting into the system
     * @return void
     */
    public static function setDefaultKeys()
    {

        //App name
        settingLoad('app.name', 'Oauth2 Server');
        settingLoad('app.org_name', 'Server org');

        //expires time for reset password
        settingLoad('auth.passwords.users.expire', 10);
        //expires time to try another request 
        settingLoad('auth.passwords.users.throttle', 10);

        //------------------------REDIS CONFIGURATION-------------------//
        //redis default settings
        settingLoad('database.redis.default.url', null);
        settingLoad('database.redis.default.host', '127.0.0.1');
        settingLoad('database.redis.default.username', null);
        settingLoad('database.redis.default.password', null);
        settingLoad('database.redis.default.port', '6379');
        settingLoad('database.redis.default.database', 0);

        //redis cache settings
        settingLoad('database.redis.cache.url', null);
        settingLoad('database.redis.cache.host', '127.0.0.1');
        settingLoad('database.redis.cache.username', null);
        settingLoad('database.redis.cache.password', null);
        settingLoad('database.redis.cache.port', '6379');
        settingLoad('database.redis.cache.database', 1);
        //------------------------END REDIS CONFIGURATION-------------------//

        //------------------------CACHE CONFIGURATION-------------------//
        settingLoad('cache.default', 'file');
        settingLoad('cache.expires', 30);
        settingLoad('cache.prefix', Str::slug(config('app.name', 'oauth2_server'), '_') . '_cache_');

        settingLoad('cache.stores.database.connection', null);
        settingLoad('cache.stores.database.table', 'cache');

        settingLoad('cache.stores.redis.connection', 'cache');
        settingLoad('cache.stores.redis.lock_connection', 'default');

        settingLoad('cache.stores.memcached.persistent_id', null);
        settingLoad('cache.stores.memcached.sasl.username', null);
        settingLoad('cache.stores.memcached.sasl.password', null);
        settingLoad('cache.stores.memcached.servers.0.host', '127.0.0.1');
        settingLoad('cache.stores.memcached.servers.0.port', 11211);
        settingLoad('cache.stores.memcached.servers.0.weight', 100);

        settingLoad('cache.stores.dynamodb.key', null);
        settingLoad('cache.stores.dynamodb.secret', null);
        settingLoad('cache.stores.dynamodb.region', 'us-east-1');
        settingLoad('cache.stores.dynamodb.table', 'cache');
        settingLoad('cache.stores.dynamodb.endpoint', null);
        //------------------------END CACHE CONFIGURATION-------------------//


        //---------------------QUEUES CONFIG--------------------///
        //default queues
        settingLoad('queue.default', 'database');

        //Sync setting
        settingLoad('queue.connections.sync.driver', 'sync');

        //Database settings
        settingLoad('queue.connections.database.driver', 'database');
        settingLoad('queue.connections.database.table', 'jobs');
        settingLoad('queue.connections.database.queue', 'default');
        settingLoad('queue.connections.database.retry_after', 90);
        settingLoad('queue.connections.database.after_commit', false);

        //beanstalkd Settings
        settingLoad('queue.connections.beanstalkd.driver', 'beanstalkd');
        settingLoad('queue.connections.beanstalkd.host', 'localhost');
        settingLoad('queue.connections.beanstalkd.queue', 'default');
        settingLoad('queue.connections.beanstalkd.retry_after', 90);
        settingLoad('queue.connections.beanstalkd.block_for', 0);
        settingLoad('queue.connections.beanstalkd.after_commit', false);

        //AWS settings
        settingLoad('queue.connections.sqs.driver', 'sqs');
        settingLoad('queue.connections.sqs.key', null);
        settingLoad('queue.connections.sqs.secret', null);
        settingLoad('queue.connections.sqs.prefix', 'https://sqs.us-east-1.amazonaws.com/your-account-id');
        settingLoad('queue.connections.sqs.queue', 'default');
        settingLoad('queue.connections.sqs.suffix', null);
        settingLoad('queue.connections.sqs.region', 'us-east-1');
        settingLoad('queue.connections.sqs.after_commit', false);

        //Redis Settings
        settingLoad('queue.connections.redis.driver', 'redis');
        settingLoad('queue.connections.redis.connection', 'default');
        settingLoad('queue.connections.redis.queue', 'default');
        settingLoad('queue.connections.redis.retry_after', 90);
        settingLoad('queue.connections.redis.block_for', null);
        settingLoad('queue.connections.redis.after_commit', false);

        //Fail queue settings
        settingLoad('queue.failed.driver', 'database-uuids');
        settingLoad('queue.failed.database', 'mysql');
        settingLoad('queue.failed.table', 'failed_jobs');
        //---------------------END QUEUES CONFIG--------------------///

        //----------FILESYSTEM SETTINGS------------------------------------------
        settingLoad('filesystems.default', 'local');
        settingLoad('filesystems.disks.local.driver', 'local');
        settingLoad('filesystems.disks.local.root', storage_path('app'));
        settingLoad('filesystems.disks.local.throw', false);

        settingLoad('filesystems.disks.public.driver', 'local');
        settingLoad('filesystems.disks.public.root', storage_path('app/public'));
        settingLoad('filesystems.disks.public.url', config('app.url', null) . '/storage');
        settingLoad('filesystems.disks.public.visibility', 'public');
        settingLoad('filesystems.disks.public.throw', false);

        settingLoad('filesystems.disks.s3.driver', 's3');
        settingLoad('filesystems.disks.s3.key', null);
        settingLoad('filesystems.disks.s3.secret', null);
        settingLoad('filesystems.disks.s3.region', null);
        settingLoad('filesystems.disks.s3.bucket', null);
        settingLoad('filesystems.disks.s3.url', null);
        settingLoad('filesystems.disks.s3.endpoint', null);
        settingLoad('filesystems.disks.s3.use_path_style_endpoint', false);
        settingLoad('filesystems.disks.s3.throw', false);

        settingLoad('filesystems.links.public', public_path('storage'));
        settingLoad('filesystems.links.storage', storage_path('app/public'));


        //-------EMAIL SETTINGS -------------------------
        settingLoad('mail.default', 'smtp');

        settingLoad('mail.mailers.smtp.transport', 'smtp');
        settingLoad('mail.mailers.smtp.host', 'smtp.mailgun.org');
        settingLoad('mail.mailers.smtp.port', 587);
        settingLoad('mail.mailers.smtp.encryption', 'tls');
        settingLoad('mail.mailers.smtp.username', null);
        settingLoad('mail.mailers.smtp.password', null);
        settingLoad('mail.mailers.smtp.timeout', null);
        settingLoad('mail.mailers.smtp.local_domain', null);

        settingLoad('mail.mailers.ses.transport', 'ses');
        settingLoad('mail.mailers.mailgun.transport', 'mailgun');
        settingLoad('mail.mailers.postmark.transport', 'postmark');

        settingLoad('mail.mailers.sendmail.transport', 'sendmail');

        settingLoad('mail.mailers.log.transport', 'log');
        settingLoad('mail.mailers.log.channel', 'MAIL_LOG_CHANNEL');

        settingLoad('mail.mailers.array.transport', 'array');

        settingLoad('mail.mailers.failover.transport', 'failover');
        //settingLoad('mail.mailers.failover.mailers', ['smtp', 'log']);

        settingLoad('mail.from.address', 'hello@example.com');
        settingLoad('mail.from.name', 'Example');

        //---------Setting services ---------------
        settingLoad('services.mailgun.domain', null);
        settingLoad('services.mailgun.secret', null);
        settingLoad('services.mailgun.endpoint', null);
        settingLoad('services.mailgun.scheme', 'https');

        settingLoad('services.passport.token', null);

        settingLoad('services.ses.key', null);
        settingLoad('services.ses.secret', null);
        settingLoad('services.ses.region', null);

        settingLoad('services.captcha.driver', "hcaptcha");
        settingLoad('services.captcha.enabled', false);

        settingLoad('services.captcha.providers.turnstile.api', 'https://challenges.cloudflare.com/turnstile/v0/siteverify');
        settingLoad('services.captcha.providers.turnstile.secret', null);
        settingLoad('services.captcha.providers.turnstile.sitekey', null);

        settingLoad('services.captcha.providers.hcaptcha.api', 'https://hcaptcha.com/siteverify');
        settingLoad('services.captcha.providers.hcaptcha.secret', null);
        settingLoad('services.captcha.providers.hcaptcha.sitekey', null);

        //Payment settings 
        settingLoad('billing.methods.stripe.name', 'Credit Card (Stripe)');
        settingLoad('billing.methods.stripe.icon', 'mdi-credit-card-outline');
        settingLoad('billing.methods.stripe.enable', true);
        settingLoad('services.stripe.secret', null);
        settingLoad('services.stripe.key', null);
        settingLoad('services.stripe.webhook_secret', null);

        settingLoad('billing.methods.offline.name', 'Offline');
        settingLoad('billing.methods.offline.icon', 'mdi-cash-register');
        settingLoad('billing.methods.offline.enable', true);

        SettingLoad('billing.renew.enable', false);
        SettingLoad('billing.renew.hours_before', 10);
        SettingLoad('billing.renew.bonus_enabled', false);
        SettingLoad('billing.renew.grace_period_days', 5);

        //System settings
        settingLoad('system.schema_mode', "https");
        settingLoad('system.home_page', "/");
        settingLoad('system.cookie_name', "oauth2_server");
        settingLoad('system.passport_token_services', null);
        settingLoad('system.verify_account_time', 5);
        settingLoad('system.disable_create_user_by_command', false);
        settingLoad('system.destroy_user_after', 30);
        settingLoad('system.code_2fa_email_expires', 5);
        settingLoad('system.csp_enabled', true);
        settingLoad('system.redirect_to', "/account");
        settingLoad('system.privacy_url', null);
        settingLoad('system.terms_url', null);
        settingLoad('system.policy_cookies', null);

        //Session settings
        //settingLoad('session.driver', 'database');
        settingLoad('session.lifetime', 7200);
        settingLoad('session.expire_on_close', false);
        settingLoad('session.encrypt', false);
        settingLoad('session.table', 'sessions');
        settingLoad('session.cookie', 'oauth2_session');
        settingLoad('session.xcsrf-token', 'oauth2_csrf');
        settingLoad('session.path', '/');
        settingLoad('session.secure', true);
        settingLoad('session.http_only', true);
        settingLoad('session.partitioned', false);

        // Settings routes
        settingLoad('routes.users.developers', false);
        settingLoad('routes.users.api', false);
        settingLoad('routes.users.clients', false);
        settingLoad('routes.guest.register', true);
    }

    /**
     * Setting default values for files system
     * @return void
     */
    public static function getFileSystems()
    {
        Config::set('filesystems.default', settingItem('filesystems.default', 'local'));
        Config::set('filesystems.disks.local.driver', settingItem('filesystems.disks.local.driver', 'local'));
        Config::set('filesystems.disks.local.root', settingItem('filesystems.disks.local.root', storage_path('app')));
        Config::set('filesystems.disks.local.throw', settingItem('filesystems.disks.local.throw', false));

        Config::set('filesystems.disks.public.driver', settingItem('filesystems.disks.public.driver', 'local'));
        Config::set('filesystems.disks.public.root', settingItem('filesystems.disks.public.root', storage_path('app/public')));
        Config::set('filesystems.disks.public.url', settingItem('filesystems.disks.public.url', config('app.url', null) . '/storage'));
        Config::set('filesystems.disks.public.visibility', settingItem('filesystems.disks.public.visibility', 'public'));
        Config::set('filesystems.disks.public.throw', settingItem('filesystems.disks.public.throw', false));

        Config::set('filesystems.disks.s3.driver', settingItem('filesystems.disks.s3.driver', 's3'));
        Config::set('filesystems.disks.s3.key', settingItem('filesystems.disks.s3.key', null));
        Config::set('filesystems.disks.s3.secret', settingItem('filesystems.disks.s3.secret', null));
        Config::set('filesystems.disks.s3.region', settingItem('filesystems.disks.s3.region', null));
        Config::set('filesystems.disks.s3.bucket', settingItem('filesystems.disks.s3.bucket', null));
        Config::set('filesystems.disks.s3.url', settingItem('filesystems.disks.s3.url', null));
        Config::set('filesystems.disks.s3.endpoint', settingItem('filesystems.disks.s3.endpoint', null));
        Config::set('filesystems.disks.s3.use_path_style_endpoint', settingItem('filesystems.disks.s3.use_path_style_endpoint', false));
        Config::set('filesystems.disks.s3.throw', settingItem('filesystems.disks.s3.throw', false));

        Config::set('filesystems.links.public', settingItem('filesystems.links.public', public_path('storage')));
        Config::set('filesystems.links.storage', settingItem('filesystems.links.storage', storage_path('app/public')));
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


    /**
     * Email settings
     * @return void
     */
    public static function getEmailSettings()
    {
        Config::set('mail.default', settingItem('mail.default', 'smtp'));

        Config::set('mail.mailers.smtp.transport', settingItem('mail.mailers.smtp.transport', 'smtp'));
        Config::set('mail.mailers.smtp.host', settingItem('mail.mailers.smtp.host', 'smtp.mailgun.org'));
        Config::set('mail.mailers.smtp.port', settingItem('mail.mailers.smtp.port', 587));
        Config::set('mail.mailers.smtp.encryption', settingItem('mail.mailers.smtp.encryption', 'tls'));
        Config::set('mail.mailers.smtp.username', settingItem('mail.mailers.smtp.username', null));
        Config::set('mail.mailers.smtp.password', settingItem('mail.mailers.smtp.password', null));
        Config::set('mail.mailers.smtp.timeout', settingItem('mail.mailers.smtp.timeout', null));
        Config::set('mail.mailers.smtp.local_domain', settingItem('mail.mailers.smtp.local_domain', null));

        Config::set('mail.mailers.ses.transport', settingItem('mail.mailers.ses.transport', 'ses'));
        Config::set('mail.mailers.mailgun.transport', settingItem('mail.mailers.mailgun.transport', 'mailgun'));
        Config::set('mail.mailers.postmark.transport', settingItem('mail.mailers.postmark.transport', 'postmark'));

        Config::set('mail.mailers.sendmail.transport', settingItem('mail.mailers.sendmail.transport', 'sendmail'));

        Config::set('mail.mailers.log.transport', settingItem('mail.mailers.log.transport', 'log'));
        Config::set('mail.mailers.log.channel', settingItem('mail.mailers.log.channel', 'MAIL_LOG_CHANNEL'));

        Config::set('mail.mailers.array.transport', settingItem('mail.mailers.array.transport', 'array'));

        Config::set('mail.mailers.failover.transport', settingItem('mail.mailers.failover.transport', 'failover'));

        Config::set('mail.from.address', settingItem('mail.from.address', 'hello@example.com'));
        Config::set('mail.from.name', settingItem('mail.from.name', 'Example'));
    }


    public static function getServicesSettings()
    {
        Config::set('services.mailgun.domain', settingItem('services.mailgun.domain', null));
        Config::set('services.mailgun.secret', settingItem('services.mailgun.secret', null));
        Config::set('services.mailgun.endpoint', settingItem('services.mailgun.endpoint', null));
        Config::set('services.mailgun.scheme', settingItem('services.mailgun.scheme', 'https'));

        Config::set('services.passport.token', settingItem('services.passport.token', null));

        Config::set('services.ses.key', settingItem('services.ses.key', null));
        Config::set('services.ses.secret', settingItem('services.ses.secret', null));
        Config::set('services.ses.region', settingItem('services.ses.region', null));

        Config::set('services.captcha.driver', settingItem('services.captcha.driver', 'hcaptcha'));
        Config::set('services.captcha.enabled', settingItem('services.captcha.enabled', false));

        Config::set('services.captcha.providers.turnstile.api', settingItem('services.captcha.providers.turnstile.api', 'https://challenges.cloudflare.com/turnstile/v0/siteverify'));
        Config::set('services.captcha.providers.turnstile.secret', settingItem('services.captcha.providers.turnstile.secret', null));
        Config::set('services.captcha.providers.turnstile.sitekey', settingItem('services.captcha.providers.turnstile.sitekey', null));

        Config::set('services.captcha.providers.hcaptcha.api', settingItem('services.captcha.providers.hcaptcha.api', 'https://hcaptcha.com/siteverify'));
        Config::set('services.captcha.providers.hcaptcha.secret', settingItem('services.captcha.providers.hcaptcha.secret', null));
        Config::set('services.captcha.providers.hcaptcha.sitekey', settingItem('services.captcha.providers.hcaptcha.sitekey', null));
    }

    /**
     * Payment settings
     * @return void
     */
    public static function getPaymentSettings()
    {

        Config::set('billing.methods.stripe.name', settingItem('billing.methods.stripe.name', 'Credit Card (Stripe)'));
        Config::set('billing.methods.stripe.icon', settingItem('billing.methods.stripe.icon', 'mdi-credit-card-outline'));
        Config::set('billing.methods.stripe.enable', settingItem('billing.methods.stripe.enable', true));
        Config::set('services.stripe.secret', settingItem('services.stripe.secret', null));
        Config::set('services.stripe.key', settingItem('services.stripe.key', null));
        Config::set('services.stripe.webhook_secret', settingItem('services.stripe.webhook_secret', null));

        Config::set('billing.methods.offline.name', settingItem('billing.methods.offline.name', 'Peer 2 Peer'));
        Config::set('billing.methods.offline.icon', settingItem('billing.methods.offline.icon', 'mdi-cash-register'));
        Config::set('billing.methods.offline.enable', settingItem('billing.methods.offline.enable', true));

        Config::set('billing.renew.enable', settingItem('billing.renew.enable', false));
        Config::set('billing.renew.hours_before', settingItem('billing.renew.hours_before', 10));
        Config::set('billing.renew.bonus_enabled', settingItem('billing.renew.bonus_enabled', false));
        Config::set('billing.renew.grace_period_days', settingItem('billing.renew.grace_period_days', 5));
    }

    /**
     * Setting system
     * @return void
     */
    public static function getSystemSetting()
    {
        Config::set('system.schema_mode', settingItem('system.schema_mode', "https"));
        Config::set('system.home_page', settingItem('system.home_page', "/"));
        Config::set('system.cookie_name', settingItem('system.cookie_name', null));
        Config::set('system.passport_token_services', settingItem('system.passport_token_services', null));
        Config::set('system.verify_account_time', settingItem('system.verify_account_time', 5));
        Config::set('system.disable_create_user_by_command', settingItem('system.disable_create_user_by_command', false));
        Config::set('system.destroy_user_after', settingItem('system.destroy_user_after', 30));
        Config::set('system.code_2fa_email_expires', settingItem('system.code_2fa_email_expires', 5));
        Config::set('system.csp_enabled', settingItem('system.csp_enabled', true));
        Config::set('system.redirect_to', settingItem('system.redirect_to', null));
        Config::set('system.privacy_url', settingItem('system.privacy_url', null));
        Config::set('system.terms_url', settingItem('system.terms_url', null));
        Config::set('system.policy_cookies', settingItem('system.policy_cookies', null));

        Config::set('passport.personal_access_client.id', settingItem('passport.personal_access_client.id', null));
        Config::set('passport.personal_access_client.secret', settingItem('passport.personal_access_client.secret', null));
    }

    /**
     * Summary of getSessionSettings
     * @return void
     */
    public static function getSessionSettings()
    {
        Config::set('session.driver', 'database');// default session driver
        Config::set('session.lifetime', settingItem('session.lifetime', 7200));
        Config::set('session.expire_on_close', settingItem('session.expire_on_close', false));
        Config::set('session.encrypt', settingItem('session.encrypt', false));
        Config::set('session.table', settingItem('session.table', 'sessions'));
        Config::set('session.cookie', settingItem('session.cookie', 'oauth2_session'));
        Config::set('session.xcsrf-token', settingItem('session.xcsrf-token', 'oauth2_csrf'));
        Config::set('session.path', settingItem('session.path', '/'));
        Config::set('session.secure', settingItem('session.secure', true));
        Config::set('session.http_only', settingItem('session.http_only', true));
        Config::set('session.partitioned', settingItem('session.partitioned', false));
    }

    public static function getCacheSettings()
    {
        Config::set('cache.default', settingItem('cache.default', 'file', false));
        Config::set('cache.expires', settingItem('cache.expires', 30, false));
        Config::set('cache.prefix', settingItem('cache.prefix', null, false));

        Config::set('cache.stores.database.connection', settingItem('cache.stores.database.connection', null, null));
        Config::set('cache.stores.database.table', settingItem('cache.stores.database.table', 'cache', null));

        Config::set('cache.stores.redis.connection', settingItem('cache.stores.redis.connection', 'cache', null));
        Config::set('cache.stores.redis.lock_connection', settingItem('cache.stores.redis.lock_connection', 'default', null));

        Config::set('cache.stores.memcached.persistent_id', settingItem('cache.stores.memcached.persistent_id', null, null));
        Config::set('cache.stores.memcached.sasl.username', settingItem('cache.stores.memcached.sasl.username', null, null));
        Config::set('cache.stores.memcached.sasl.password', settingItem('cache.stores.memcached.sasl.password', null, null));
        Config::set('cache.stores.memcached.servers.0.host.', settingItem('cache.stores.memcached.servers.0.host', '127.0.0.1', null));
        Config::set('cache.stores.memcached.servers.0.port', settingItem('cache.stores.memcached.servers.0.port', 11211, null));
        Config::set('cache.stores.memcached.servers.0.weight', settingItem('cache.stores.memcached.servers.0.weight', 100, null));

        Config::set('cache.stores.dynamodb.key', settingItem('cache.stores.dynamodb.key', null, null));
        Config::set('cache.stores.dynamodb.secret', settingItem('cache.stores.dynamodb.secret', null, null));
        Config::set('cache.stores.dynamodb.region', settingItem('cache.stores.dynamodb.region', 'us-east-1', null));
        Config::set('cache.stores.dynamodb.table', settingItem('cache.stores.dynamodb.table', 'cache', null));
        Config::set('cache.stores.dynamodb.table', settingItem('cache.stores.dynamodb.endpoint', null, null));
    }


    public static function getRoutesSettings()
    {
        Config::set('routes.users.developers', settingItem('routes.users.developers', false));
        Config::set('routes.users.api', settingItem('routes.users.api', false));
        Config::set('routes.users.clients', settingItem('routes.users.clients', false));
        Config::set('routes.guest.register', settingItem('routes.guest.register', true));
    }
}
