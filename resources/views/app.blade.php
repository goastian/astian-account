@extends('layouts.app')

@section('content')
    <div id="app" data-nonce="{{ $nonce }}" data-app-name="{{ settingItem('app.name') }}"></div>
@endsection
