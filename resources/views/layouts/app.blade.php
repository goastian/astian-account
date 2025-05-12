<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    @include('layouts.parts.meta')

    <link rel="icon" href="{{ config('app.url') }}/favicon.png" type="image/png">

    <title>{{ config('app.name', 'Oauth2 Server') }}</title>

    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/tailwind.css') }}">
    @inertiaHead
</head>

<body>

    @yield('header')

    @yield('content')

    @yield('footer')

    <!-- Scripts -->
    <script src="{{ mix('/js/app.js') }}"></script>
</body>

</html>
