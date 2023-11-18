<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => explode(',', env('CORS_PATHS')),

    'allowed_methods' => explode(',', env('CORS_METHODS')),

    'allowed_origins' =>  explode(',', env('CORS_ORIGINS')),

    'allowed_origins_patterns' => explode(',', env('CORS_PATTERNS')),

    'allowed_headers' => explode(',', env('CORS_ALLOW_HEADERS')),

    'exposed_headers' => explode(',', env('CORS_EXPOSED_HEADERS')),

    'max_age' => env('CORS_MAX_AGE',0),

    'supports_credentials' => env('CORS_CREDENTIALS',true),

];
