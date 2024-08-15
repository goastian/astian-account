<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="OAuth2 Server to synchronize the entire Astian ecosystem">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ config('app.url') }}/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">

</head>

<body>
    <div id="app">
    </div>
    <script src="{{ mix('/js/app.js') }}"></script>
</body>

</html>
