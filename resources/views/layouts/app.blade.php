<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="OAuth2 Server for secure authentication.">
    <meta name="keywords" content="OAuth2, Laravel, API, Authentication">
    <meta name="author" content="Tu Nombre o Compañía">
    <meta name="robots" content="index, follow">

    <meta property="og:title" content="{{ settingItem('app.name', 'Oauth2 Server') }}">
    <meta property="og:description" content="OAuth2 Server for secure authentication.">
    <meta property="og:url" content="{{ settingItem('app.url') }}">
    <meta property="og:type" content="website">

    <link rel="icon" href="{{ settingItem('app.url') }}/favicon.png" type="image/png">

    <title>{{ settingItem('app.name', 'Oauth2 Server') }}</title>

    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/tailwind.css') }}">
    <script src="{{ mix('/js/pages.js') }}"></script>
</head>

<body>

    <div id="app" data-nonce="{{ $nonce }}" data-app-name="{{ settingItem('app.name') }}"></div>

    <!-- Scripts -->
    <script src="{{ mix('/js/app.js') }}"></script>
</body>

</html>
