@if (isset($title))
    <title>{{ $title }} - {{ config('app.name', 'Laravel') }}</title>
@else
    <title>{{ config('app.name', 'Laravel') }}</title>
@endif
