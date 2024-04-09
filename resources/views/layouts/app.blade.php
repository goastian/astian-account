<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ config('app.url') }}/favicon.svg" type="image/svg+xml">

    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
</head>

<body>
    <div class="wrapper">

        <div class="content">
            @yield('content')
        </div>
        <footer class="footer">
            <ul>
                <li class="footer-hover">
                    <a href="{{ env('MIX_HOME_POLICY') }}" target="_blank">{{ __('Privacy Policy') }}</a>
                </li>
                <li class="footer-hover">
                    <a href="{{ env('MIX_HOME_DEVELOPER') }}" target="_blank">{{ __('Developers') }}</a>
                </li>
                <li class="footer-hover">
                    <a href="{{ env('MIX_HOME_TERMS') }}" target="_blank">{{ __('Terms of Service') }}</a>
                </li>
                <li class="footer-hover">
                    <a href="{{ env('MIX_HOME_CONTACT') }}" target="_blank">{{ __('Contact Us') }}</a>
                </li>
            </ul>

            <ul>
                <li>
                    Copyright ©
                    {{ date('Y') }}
                    -
                    <strong> {{ config('app.name') }} </strong>, All Rights Reserved
                </li>
            </ul>
        </footer>
    </div>

    <script src="{{ mix('/js/app.js') }}"></script>
</body>

</html>
