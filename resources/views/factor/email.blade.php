@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Verify my account')])
@endsection

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="max-w-md w-full bg-white p-6 rounded-lg shadow-md">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">{{ __('Verify my account') }}</h2>
                <p class="text-gray-600 mt-2">
                    {{ __('Please check your email address and type your code in the field below.') }}</p>
            </div>

            <form action="{{ route('users.2fa.login') }}" method="post">
                @csrf
                <div class="mb-4">
                    <label for="token"
                        class="block text-sm font-medium text-gray-700">{{ __('Verification Code') }}</label>
                    <input type="text" id="token" name="token"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                        placeholder="{{ __('Enter your code') }}" value="{{ old('token') }}">
                    @if ($errors->has('token'))
                        <div class="text-sm text-red-500 mt-1">
                            {{ $errors->first('token') }}
                        </div>
                    @endif
                </div>

                <div class="mb-6 text-center">
                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <span class="mdi mdi-lock"></span> {{ __('Verify token') }}
                    </button>
                </div>

                <div class="text-center mb-6">
                    @if (session('warning'))
                        <p class="text-yellow-600 text-sm">{{ session('warning') }}</p>
                    @endif
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-sm text-blue-500 hover:underline">
                        {{ __('Is your code expired? Please try again.') }}
                    </a>
                </div>
            </form>

            <div class="mt-6 text-center">
                @if (session('status'))
                    <div class="bg-green-100 text-green-800 p-4 rounded-md">
                        <p>{{ session('status') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
