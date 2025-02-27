@extends('layouts.app')

@section('title')
    @include('layouts.parts.title', ['title' => __('Terminal')])
@endsection

@section('header')
    <nav class="bg-indigo-600 text-white py-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center px-4">
            <a href="{{ settingItem('redirect_to', 'home') }}" class="text-lg font-semibold">
                <i class="mdi mdi-home text-2xl"></i>
                {{ __('Dashboard') }}
            </a>
        </div>
    </nav>
@endsection

@section('content')
    <div id="terminal" data-app-name="{{ settingItem('app.name') }}"></div>
@endsection
