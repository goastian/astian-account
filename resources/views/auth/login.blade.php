@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Login')])
@endsection

@section('content')

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 px-4">
        <div class="w-full max-w-md bg-white shadow-2xl rounded-2xl p-8">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-extrabold text-gray-800 tracking-tight">
                    {{ config('app.name', 'Oauth2 Server') }}
                </h1>
                <p class="text-sm text-gray-500 mt-2">Welcome back! Please sign in to your account.</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <!-- Email -->
                <div>
                    <input type="email" name="email" placeholder="{{ __('Email') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                    @if ($errors->has('email'))
                        @foreach ($errors->get('email') as $item)
                            <span class="text-red-500 text-xs mt-1 block">{{ $item }}</span>
                        @endforeach
                    @endif
                </div>

                <!-- Password -->
                <div>
                    <input type="password" name="password" placeholder="{{ __('Password') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                    @if ($errors->has('password'))
                        @foreach ($errors->get('password') as $item)
                            <span class="text-red-500 text-xs mt-1 block">{{ $item }}</span>
                        @endforeach
                    @endif

                    <div class="text-right mt-2">
                        <a href="{{ route('forgot-password') }}" class="text-sm text-blue-500 hover:underline">
                            {{ __('Forgot your password?') }}
                        </a>
                    </div>
                </div>

                <x-captcha />

                <!-- Submit -->
                <div>
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl transition duration-200 font-semibold shadow-sm">
                        {{ __('Sign in') }}
                    </button>
                </div>

                <!-- Register link -->
                @if (config('system.enable_register_member', true))
                    <p class="text-center text-sm text-gray-600">
                        {{ __("Don't have an account?") }}
                        <a href="{{ route('register') }}" class="text-blue-600 hover:underline">
                            {{ __('Sign up.') }}
                        </a>
                    </p>
                @endif
            </form>
        </div>
    </div>

@endsection
