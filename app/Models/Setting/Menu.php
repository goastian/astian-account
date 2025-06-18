<?php

namespace App\Models\Setting;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Transformers\User\AuthTransformer;

class Menu
{

    /**
     * return the user data
     */
    public static function authenticated_user()
    {
        return auth()->check() ?
            fractal(Auth::user(), AuthTransformer::class)->toArray()['data'] :
            [];
    }

    /**
     * Environment keys
     * @var array
     */
    public static function shareEnvironmentKeys()
    {
        return [
            "captcha" => static::captcha(),
            "app_name" => config('app.name'),
            "user" => static::authenticated_user(),
            "user_routes" => static::userRoutes(),
            "user_dashboard" => route('users.dashboard'),
            "admin_routes" => static::adminRoutes(),
            "auth_routes" => [
                "login" => route('login'),
                "forgot_password" => route('forgot-password'),
                "register" => route('register'),
                "logout" => route('logout'),
            ],
            "guest_routes" => [
                "home_page" => url(config('system.home_page')),
                "plans" => route('plans.index')
            ],
            "admin_dashboard" => [
                "name" => "Admin",
                "route" => route("admin.dashboard"),
                "icon" => "mdi-security",
                'show' => true,
            ],
            "partner_dashboard" => [
                "name" => "Partner",
                "route" => route("partners.dashboard"),
                "icon" => "mdi-account-cash",
                'show' => true,
            ],
            "partner_routes" => static::partnerRoutes(),
            "allow_register" => config('routes.guest.register', true),
        ];
    }

    /**
     * Set the user routes
     * @return array[]
     */
    public static function userRoutes()
    {
        return
            [
                [
                    'name' => 'Account',
                    'icon' => 'mdi-account-star',
                    'show' => true,
                    'menu' => [
                        [
                            'name' => 'Me',
                            'route' => route('users.dashboard'),
                            'icon' => 'mdi-information',
                            'show' => true,
                        ],
                        [
                            'name' => 'profile',
                            'route' => route('users.profile'),
                            'icon' => 'mdi-account-details-outline',
                            'show' => true,
                        ],
                        [
                            'name' => 'Password',
                            'route' => route('users.password'),
                            'icon' => 'mdi-lock-reset',
                            'show' => true,
                        ],
                        [
                            'name' => '2FA',
                            'route' => route('users.2fa.request'),
                            'icon' => 'mdi-two-factor-authentication',
                            'show' => true,
                        ],
                        [
                            'name' => 'Subscriptions',
                            'route' => route('users.subscriptions.index'),
                            'icon' => 'mdi-gift-outline',
                            'show' => true,
                        ],
                        [
                            'name' => 'Store',
                            'route' => route('plans.index'),
                            'icon' => 'mdi-store-search',
                            'show' => true,
                        ],
                    ]
                ],
                [
                    'name' => 'Developers',
                    'icon' => 'mdi-tools',
                    'show' => intval(config('routes.users.developers')) ? true : false,
                    'menu' => [
                        [
                            'name' => 'Applications',
                            'route' => route('passport.clients.index'),
                            'icon' => 'mdi-wan',
                            'show' => intval(config('routes.users.clients')) ? true : false
                        ],
                        [
                            'name' => 'API Key',
                            'route' => route('passport.personal.tokens.index'),
                            'icon' => 'mdi-shield-key-outline',
                            'show' => intval(config('routes.users.api')) ? true : false,
                        ],
                    ]
                ],
            ];
    }


    /**
     * Set the admin routes
     * @return array{icon: string, name: string, route: string[]}
     */
    public static function adminRoutes()
    {
        return [
            [
                "name" => "Dashboard",
                "route" => route("admin.dashboard"),
                "icon" => "mdi-view-dashboard",
                'show' => true,
            ],
            [
                "name" => "Groups",
                "route" => route("admin.groups.index"),
                "icon" => "mdi-account-group",
                'show' => true,
            ],
            [
                "name" => "Roles",
                "route" => route("admin.roles.index"),
                "icon" => "mdi-format-list-group",
                'show' => true,
            ],
            [
                "name" => "Services",
                "route" => route("admin.services.index"),
                "icon" => "mdi-text-box-check",
                'show' => true,
            ],
            [
                "name" => "Users",
                "route" => route("admin.users.index"),
                "icon" => "mdi-account-multiple",
                'show' => true,
            ],
            [
                "name" => "Clients",
                "route" => route("admin.clients.index"),
                "icon" => "mdi-apps",
                'show' => true,
            ],
            [
                "name" => "Broadcasts",
                "route" => route("admin.broadcasts.index"),
                "icon" => "mdi-broadcast",
                'show' => true,
            ],
            [
                "name" => "Plans",
                "route" => route("admin.plans.index"),
                "icon" => "mdi-cash-clock",
                'show' => true,
            ],
            [
                "name" => "Transactions",
                "route" => route("admin.transactions.index"),
                "icon" => "mdi-account-cash-outline",
                'show' => true,
            ],
            [
                "name" => "Terminal",
                "route" => route("admin.terminals.index"),
                "icon" => "mdi-console",
                'show' => true,
            ],
            [
                "name" => "Settings",
                "route" => route("admin.settings.general"),
                "icon" => "mdi-cogs",
                'show' => true,
            ],
        ];
    }

    /**
     * Partner routes
     * @return array{icon: string, name: string, route: string[]}
     */
    public static function partnerRoutes()
    {
        return [
            [
                "name" => "Dashboard",
                "route" => route("partners.dashboard"),
                "icon" => "mdi-account-cash",
                'show' => true,
            ],
            [
                "name" => "Referral Link",
                "route" => route("partners.generate"),
                "icon" => "mdi-reload",
                'show' => true,
            ],
            [
                "name" => "Sales",
                "route" => route("partners.sales"),
                "icon" => "mdi-cash-multiple",
                'show' => true,
            ],
        ];
    }

    public static function captcha()
    {
        $provider = config("services.captcha.driver");
        return [
            "provider" => $provider,
            "siteKey" => config("services.captcha.providers.$provider.sitekey"),
            "status" => intval(config("services.captcha.enabled")) ? true : false,
            "providers" => array_keys(config('services.captcha.providers')),
        ];
    }
}
