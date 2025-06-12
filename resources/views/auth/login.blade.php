@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Login')])
@endsection

@section('content')

    <div class="w-full flex min-h-screen login-form relative overflow-hidden">

        <div class="absolute inset-0 z-0 overflow-hidden">
            <div
                class="absolute top-[-10%] left-[-10%] w-[300px] h-[300px] bg-purple-300 opacity-30 rounded-full filter blur-3xl animate-pulse">
            </div>
            <div
                class="absolute bottom-[-10%] right-[-5%] w-[400px] h-[400px] bg-pink-300 opacity-20 rounded-full filter blur-2xl animate-spin-slow">
            </div>
            <div
                class="absolute top-1/2 left-[20%] w-[250px] h-[250px] bg-blue-200 opacity-40 rounded-full filter blur-2xl animate-float">
            </div>
            <div
                class="absolute top-2/2 left-[50%] w-[250px] h-[250px] bg-blue-200 opacity-40 rounded-full filter blur-2xl animate-float">
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex justify-center p-8 lg:p-16 animate-slide-in-left">
            <div
                class="relative glass-card rounded-3xl p-8 lg:p-12 bg-white flex shadow-md flex-col justify-center w-full max-w-md">

                <div class="flex flex-col mb-8 gap-8">
                    <p class="text-3xl font-bold text-gray-800 tracking-tight">
                        {{ config('app.name', 'oauth2 server') }}
                    </p>
                    <div class="flex flex-col gap-4">
                        <span class="text-4xl font-bold">Welcome back!👋</span>
                        <p>Access your account quickly and securely to manage your data and settings without complications
                        </p>
                    </div>
                </div>

                <!-- Login-form -->
                <div class="w-full max-w-md space-y-8">
                    <div class="body">
                        <form action="{{ route('login') }}" method="post">
                            @csrf

                            <div class="mb-6">
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"
                                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8h18a2 2 0 002-2V8a2 2 0 00-2-2H3a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                    </svg>
                                    <input type="email" name="email" placeholder="{{ __('email') }}"
                                        class="modern-input pl-12 w-full py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required>
                                </div>
                                @if ($errors->has('email'))
                                    @foreach ($errors->get('email') as $item)
                                        <span class="text-red-500 text-sm mt-1 block">{{ $item }}</span>
                                    @endforeach
                                @endif
                            </div>
                            <div class="mb-6">
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"
                                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m6-6v6a2 2 0 01-2 2H8a2 2 0 01-2-2v-6a2 2 0 012-2h1V9a3 3 0 016 0v2h1a2 2 0 012 2z" />
                                    </svg>
                                    <input type="password" name="password" placeholder="{{ __('password') }}"
                                        class="modern-input pl-12 w-full py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required>
                                </div>
                                @if ($errors->has('password'))
                                    @foreach ($errors->get('password') as $item)
                                        <span class="text-red-500 text-sm mt-1 block">{{ $item }}</span>
                                    @endforeach
                                @endif
                            </div>
                            <a class="text-blue-500 text-sm hover:underline mt-6 block text-right"
                                href="{{ route('forgot-password') }}">{{ __('forgot your password?') }}</a>

                            <x-captcha />

                            <div class="buttons mt-6 relative">
                                <button type="submit"
                                    class="w-full green-color-login-card text-white py-3 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 ease-in-out">
                                    {{ __('sign in') }}
                                </button>
                                @if (config('system.enable_register_member', true))
                                    <p class="text-sm text-gray-600 text-start mt-6">
                                        {{ __("don't have an account?") }}
                                        <a href="{{ route('register') }}" class="text-blue-600 hover:underline">
                                            {{ __('sign up.') }}
                                        </a>
                                    </p>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lado derecho (visual) -->
        <div class="hidden flex-col gap-8 lg:flex w-1/2 items-center justify-center p-16 animate-slide-in-right">
            <div
                class="relative w-60 h-60 flex items-center justify-center rounded-full bg-red bg-white shadow-2xl animate-float logo-astian">
                <img src="/img/icon.webp" alt="Logo 3D" class="w-64 h-64 object-contain" />

                <span class="emoji text-orange-500">GO</span>
                <span class="emoji">📝</span>
                <span class="emoji">🛡️</span>
                <span class="emoji">📅</span>
                <span class="emoji">💻</span>
                <span class="emoji">🔑</span>
                <span class="emoji">☁️</span>
            </div>

            <div class="flex flex-col items-center gap-4">
                <h2 class="text-4xl text-center">Welcome to Astian</h2>
                <span class="text-base text-center max-w-100">Access your account and enjoy a secure and personalized
                    experience, with all the tools you need in one place.</span>
            </div>
        </div>
    </div>

@endsection
