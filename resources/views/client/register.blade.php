@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Register')])
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

        <div class="w-full lg:w-1/2 flex justify-center p-8 lg:p-16 animate-slide-in-left">
            <div
                class="relative glass-card rounded-3xl p-8 lg:p-12 bg-white flex shadow-md flex-col justify-center w-full max-w-lg">

                <div class="flex flex-col mb-1 gap-2">
                    <p class="text-3xl font-bold text-gray-800 tracking-tight">
                        {{ config('app.name', 'oauth2 server') }}
                    </p>
                    <div class="flex flex-col gap-2">
                        <h2 class="text-2xl font-bold text-gray-800">{{ __('Create a new Account') }}</h2>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ __('Join us and enjoy a new :name privacy and security.', ['name' => config('app.name')]) }}
                        </p>
                    </div>
                </div>

                <!-- Login-form -->
                <div class="w-full max-w-md space-y-8">
                    <div class="body">
                        <!-- Form -->
                        <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-4">
                            @csrf

                            <!-- Name -->
                            <div class="relative mb-6">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A8.963 8.963 0 0112 15a8.963 8.963 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <input type="text" name="name" placeholder="{{ __('Name') }}"
                                    value="{{ old('name') }}"
                                    class="modern-input pl-12 w-full py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Last Name -->
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A8.963 8.963 0 0112 15a8.963 8.963 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <input type="text" name="last_name" placeholder="{{ __('Last Name') }}"
                                    value="{{ old('last_name') }}"
                                    class="modern-input pl-12 w-full py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                @error('last_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <input type="text" name="website" class="hidden">

                            <!-- Email -->
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8h18a2 2 0 002-2V8a2 2 0 00-2-2H3a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                </svg>
                                <input type="email" name="email" placeholder="{{ __('Email') }}"
                                    value="{{ old('email') }}"
                                    class="modern-input pl-12 w-full py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Birthday -->
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3M3 11h18M5 19h14a2 2 0 002-2v-7H3v7a2 2 0 002 2z" />
                                </svg>
                                <input type="text" name="birthday" placeholder="{{ __('Birthday') }}"
                                    value="{{ old('birthday') }}"
                                    class="modern-input pl-12 w-full py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                @error('birthday')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m6-6v6a2 2 0 01-2 2H8a2 2 0 01-2-2v-6a2 2 0 012-2h1V9a3 3 0 016 0v2h1a2 2 0 012 2z" />
                                </svg>
                                <input type="password" name="password" placeholder="{{ __('Password') }}"
                                    class="modern-input pl-12 w-full py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m6-6v6a2 2 0 01-2 2H8a2 2 0 01-2-2v-6a2 2 0 012-2h1V9a3 3 0 016 0v2h1a2 2 0 012 2z" />
                                </svg>
                                <input type="password" name="password_confirmation"
                                    placeholder="{{ __('Password Confirmation') }}"
                                    class="modern-input pl-12 w-full py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                @error('password_confirmation')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Hidden Referral -->
                            <input type="hidden" name="referral_code" value="{{ request()->referral_code }}">

                            <!-- Terms -->
                            <div class="flex items-start gap-2 text-sm text-gray-600">
                                <input type="checkbox" name="accept_terms" id="accept_terms" value="1"
                                    class="mt-1 rounded border-gray-300 text-blue-500 focus:ring-2 focus:ring-blue-400">
                                <label for="accept_terms">
                                    {{ __('By choosing this option, you accept the') }}
                                    <a href="{{ config('system.service_agreement') }}" target="_blank"
                                        class="text-blue-600 hover:underline">
                                        {{ __('Services Agreement') }}</a> {{ __('and') }}
                                    <a href="{{ config('system.service_statement') }}" target="_blank"
                                        class="text-blue-600 hover:underline">
                                        {{ 'Privacy Statement.' }}
                                    </a>
                                </label>
                            </div>
                            @error('accept_terms')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror


                            <div class="flex items-start gap-2 text-sm text-gray-600">
                                <input type="checkbox" name="accept_cookies" id="accept_cookies" value="1"
                                    class="mt-1 rounded border-gray-300 text-blue-500 focus:ring-2 focus:ring-blue-400">
                                <label for="accept_cookies">
                                    {{ __('By choosing this option, you accept the') }}
                                    <a href="{{ config('system.policy_cookies') }}" target="_blank"
                                        class="text-blue-600 hover:underline">
                                        {{ __('Cookies Policy') }}.
                                    </a>
                                </label>
                            </div>

                            @error('accept_cookies')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror


                            <x-captcha />

                            <!-- Submit -->
                            <div>
                                <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
