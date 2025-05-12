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
            "app_name" => config('app.name'),
            "user" => static::authenticated_user(),
            "user_routes" => static::userRoutes(),
            "login" => route('login'),
            "register" => route('register'),
            "forgot_password" => route('forgot-password'),
            "logout" => route('logout'),
            "user_dashboard" => route('users.dashboard'),
            "admin_routes" => static::adminRoutes(),
            "admin_dashboard" => [
                "name" => "Admin",
                "route" => route("admin.users.index"),
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
            "allow_register" => config('system.enable_register_member', true),
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
                        ['name' => 'Me', 'route' => route('users.dashboard'), 'icon' => 'mdi-information'],
                        ['name' => 'profile', 'route' => route('users.profile'), 'icon' => 'mdi-account-details-outline'],
                        ['name' => 'Password', 'route' => route('users.password'), 'icon' => 'mdi-lock-reset'],
                        ['name' => '2FA', 'route' => route('users.2fa.request'), 'icon' => 'mdi-two-factor-authentication'],
                        ['name' => 'Subscriptions', 'route' => route('users.subscriptions.index'), 'icon' => 'mdi-gift-outline'],
                        ['name' => 'Store', 'route' => route('plans.index'), 'icon' => 'mdi-store-search'],
                    ]
                ],
                [
                    'name' => 'Developers',
                    'icon' => 'mdi-tools',
                    'show' => false,
                    'menu' => [
                        ['name' => 'Applications', 'route' => route('passport.clients.index'), 'icon' => 'mdi-wan'],
                        ['name' => 'API Key', 'route' => route('passport.personal.tokens.index'), 'icon' => 'mdi-shield-key-outline'],
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
                "name" => "My Account",
                "route" => route("users.dashboard"),
                "icon" => "mdi-account-cog-outline",
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
                "name" => "Settings",
                "route" => route("settings.general"),
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
}
