@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Register')])
@endsection

@section('content')
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 px-4 py-8 overflow-y-auto">
        <div class="w-full max-w-lg bg-white shadow-2xl rounded-2xl p-8">
            <!-- Header -->
            <div class="text-center mb-3">
                <h2 class="text-2xl font-bold text-gray-800">{{ __('Create a new Account') }}</h2>
                <p class="text-sm text-gray-600 mt-1">
                    {{ __('Join us and enjoy a new :name privacy and security.', ['name' => config('app.name')]) }}
                </p>
            </div>

            <!-- Form -->
            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Name -->
                <div>
                    <input type="text" name="name" placeholder="{{ __('Name') }}" value="{{ old('name') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Last Name -->
                <div>
                    <input type="text" name="last_name" placeholder="{{ __('Last Name') }}"
                        value="{{ old('last_name') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    @error('last_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <input type="email" name="email" placeholder="{{ __('Email') }}" value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Birthday -->
                <div>
                    <input type="text" name="birthday" placeholder="{{ __('Birthday') }}" value="{{ old('birthday') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    @error('birthday')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <input type="password" name="password" placeholder="{{ __('Password') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <input type="password" name="password_confirmation" placeholder="{{ __('Password Confirmation') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
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
                        <a href="{{ config('system.home_page') }}" target="_blank" class="text-blue-600 hover:underline">
                            {{ config('app.name') }}
                        </a>,
                        <a href="{{ config('system.policy_services') }}" target="_blank"
                            class="text-blue-600 hover:underline">
                            Services Agreement, Privacy Statement
                        </a>, {{ __('and') }}
                        <a href="{{ config('system.policy_cookies') }}" target="_blank"
                            class="text-blue-600 hover:underline">
                            Cookies Policy
                        </a>.
                    </label>
                </div>
                @error('accept_terms')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

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
@endsection
