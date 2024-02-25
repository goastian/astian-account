<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="favicon.png" type="image/png">

    <link rel="stylesheet"
        href="{{ app()->environment('production') ? secure_asset('css/app.css') : asset('css/app.css') }}">

    <script src="{{ app()->environment('production') ? secure_asset('js/app.js') : asset('js/app.js') }}" defer></script>
</head>

<body>
    <div class="wrapper">

        <div class="content">
            @yield('content')
        </div>
        <footer class="footer text-color bg-primary pt-4">
            <ul class="nav">
                <li class="nav-item mx-2">
                    <a href="{{ env('MIX_HOME_POLICY') }}">Privacy Policy</a>
                </li>
                <li class="nav-item mx-2">
                    <a href="{{ env('MIX_HOME_DEVELOPER') }}">Developers</a>
                </li>
                <li class="nav-item mx-2">
                    <a href="{{ env('MIX_HOME_TERMS') }}">Terms of Service</a>
                </li>
                <li class="nav-item mx-2">
                    <a href="{{ env('MIX_HOME_CONTACT') }}">Contact Us</a>
                </li>
            </ul>
            <div class="text-center text-light fw-bold">
                Copyright © {{ date('Y') }} - <strong> {{ config('app.name') }} </strong>, All Rights
                Reserved
            </div>
        </footer>
    </div>
</body>

</html>
