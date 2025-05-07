@extends('layouts.app')

@section('content')
    <div id="app" data-nonce="{{ $nonce }}" data-app-name="{{ config('app.name') }}"></div>
@endsection
