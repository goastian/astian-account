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
            "user_dashboard" => route('users.dashboard'),
            "admin_routes" => static::adminRoutes(),
            "admin_dashboard" => [
                "name" => "Admin",
                "route" => route("admin.users.index"),
                "icon" => "mdi-security",
            ],

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
            ],
            [
                "name" => "Groups",
                "route" => route("admin.groups.index"),
                "icon" => "mdi-account-group",
            ],
            [
                "name" => "Roles",
                "route" => route("admin.roles.index"),
                "icon" => "mdi-format-list-group",
            ],
            [
                "name" => "Services",
                "route" => route("admin.services.index"),
                "icon" => "mdi-text-box-check",
            ],
            [
                "name" => "Users",
                "route" => route("admin.users.index"),
                "icon" => "mdi-account-multiple",
            ],
            [
                "name" => "Clients",
                "route" => route("admin.clients.index"),
                "icon" => "mdi-apps",
            ],
            [
                "name" => "Broadcasts",
                "route" => route("admin.broadcasts.index"),
                "icon" => "mdi-broadcast",
            ],
            [
                "name" => "Plans",
                "route" => route("admin.plans.index"),
                "icon" => "mdi-cash-clock",
            ],
            [
                "name" => "Transactions",
                "route" => route("admin.transactions.index"),
                "icon" => "mdi-account-cash-outline",
            ],
            [
                "name" => "Terminal",
                "route" => route("admin.terminals.index"),
                "icon" => "mdi-console",
            ],
            [
                "name" => "Settings",
                "route" => route("settings.general"),
                "icon" => "mdi-cogs",
            ],
        ];
    }
}
