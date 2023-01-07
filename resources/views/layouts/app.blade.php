<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    @env(['prod', 'production'])
    <link rel="stylesheet" href="{{ secure_asset('dist/app.css') }}">
    @endenv

    @env(['local', 'dev'])
    <link rel="stylesheet" href="{{ asset('dist/app.css') }}">
    @endenv
</head>

<body>
    <div id="app" class="container-fluid">
        @yield('content')
    </div>


    @env(['prod', 'production'])
    <script src="{{ secure_asset('dist/app.js') }}"></script>
    @endenv

    @env(['local', 'dev'])
    <script src="{{ asset('dist/app.js') }}"></script>
    @endenv

</body>

</html>
