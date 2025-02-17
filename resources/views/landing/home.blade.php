@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => config('app.name')])
@endsection

@section('header')
    @include('layouts.parts.header')
@endsection

@section('content')
    <main class="container mx-auto px-6 py-10 grow">
        <section id="about" class="mb-6 text-center">
            <h2 class="text-2xl font-semibold">{{ config('app.name') }}</h2>
            <p class="text-base text-gray-700">
                {{ __('Securely manage user access and authentication with modern protocols like OAuth 2.0.') }}
            </p>
        </section>

        <section id="features" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-white shadow rounded p-4">
                <h3 class="text-lg font-bold mb-2">{{ __('Key Features') }}</h3>
                <ul class="list-disc list-inside text-gray-700">
                    <li>{{ __('User and role management') }}</li>
                    <li>{{ __('OAuth 2.0 compatibility') }}</li>
                </ul>
            </div>

            <div class="bg-white shadow rounded p-4">
                <h3 class="text-lg font-bold mb-2">{{ __('How It Works') }}</h3>
                <p class="text-gray-700">
                    {{ __('Issue secure tokens for applications and ensure authorized access to resources.') }}
                </p>
            </div>
        </section>

        <section id="benefits" class="text-center">
            <h3 class="text-xl font-semibold mb-2">{{ __('Why Choose Us?') }}</h3>
            <p class="text-base text-gray-700 mb-4">
                {{ __('Enhance security and scalability with centralized authentication.') }}
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white shadow rounded p-4">
                    <h4 class="text-base font-bold mb-2">{{ __('Increased Security') }}</h4>
                    <p class="text-gray-700">{{ __('Protect sensitive data with encryption.') }}</p>
                </div>
                <div class="bg-white shadow rounded p-4">
                    <h4 class="text-base font-bold mb-2">{{ __('Centralized Control') }}</h4>
                    <p class="text-gray-700">{{ __('Manage access across multiple applications.') }}</p>
                </div>
                <div class="bg-white shadow rounded p-4">
                    <h4 class="text-base font-bold mb-2">{{ __('Seamless Integration') }}</h4>
                    <p class="text-gray-700">{{ __('Easily integrate with existing systems.') }}</p>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('footer')
    @include('layouts.parts.footer')
@endsection
