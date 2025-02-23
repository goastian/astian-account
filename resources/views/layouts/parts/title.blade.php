@if (isset($title))
    <title>{{ $title }} - {{ settingItem('app.name', 'Oauth2 Server') }}</title>
@else
    <title>{{ settingItem('app.name', 'Oauth2 Server') }}</title>
@endif
