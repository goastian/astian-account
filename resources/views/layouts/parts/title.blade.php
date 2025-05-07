@if (isset($title))
    <title>{{ $title }} - {{ config('app.name', 'Oauth2 Server') }}</title>
@else
    <title>{{ config('app.name', 'Oauth2 Server') }}</title>
@endif
