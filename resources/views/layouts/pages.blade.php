<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ config('app.url') }}/favicon.svg" type="image/svg+xml">

    <link rel="stylesheet" href="{{ mix('/css/pages.css') }}">
</head>

<body>
    <div class="wrapper">

        <div class="content">
            @yield('content')
        </div>

        @include('layouts.footer')
    </div>
</body>

</html>
