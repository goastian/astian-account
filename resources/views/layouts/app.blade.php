<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    @include('layouts.parts.meta')

    <link rel="icon" href="{{ settingItem('app.url') }}/favicon.png" type="image/png">

    <title>{{ settingItem('app.name', 'Oauth2 Server') }}</title>

    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/tailwind.css') }}">
</head>

<body>

    <div class="min-h-screen flex flex-col bg-gradient-to-b from-white to-gray-50">

    @yield('header')

    @yield('content')

    @yield('footer')

    </div>

    <!-- Scripts -->
    <script src="{{ mix('/js/app.js') }}"></script>
</body>

</html>
