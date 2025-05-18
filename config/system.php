<?php

return [

    //redirect after login
    "redirect_to" => "/account",

    // Force the schema mode (http or https)
    "schema_mode" => "https",

    // Default redirect after login or main page
    "home_page" => "/",

    // Custom cookie name for the session (null = default)
    "cookie_name" => null,

    // Token services for Laravel Passport (null = disabled)
    "passport_token_services" => null,

    // Time in minutes to allow account verification
    "verify_account_time" => 5,

    // Prevent creating users via Artisan commands
    "disable_create_user_by_command" => false,

    // Automatically delete users after X days (e.g., inactive or not verified)
    "destroy_user_after" => 30,

    // Expiration time (in minutes) for the 2FA code sent via email
    "code_2fa_email_expires" => 5,

    // Allow or block public registration
    "enable_register_member" => true,

    // Enable Content Security Policy (CSP) headers
    "csp_enabled" => false,

    //Policies
    "service_agreement" => null,
    "service_statement" => null,
    "policy_cookies" => null,
];
