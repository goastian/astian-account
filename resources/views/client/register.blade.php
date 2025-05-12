@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Register')])
@endsection

@section('content')
    <div class="form-register-client flex justify-center items-center min-h-screen bg-gray-50">

        <div class="form bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
            <div class="head text-center mb-3">
                <p class="text-2xl font-semibold text-gray-800">{{ __('Create a new Account') }}</p>
                <p class="text-gray-600 mt-1">
                    {{ __('Join us and enjoy a new :name privacy and security.', ['name' => config('app.name')]) }}
                </p>
            </div>
            <div class="body">
                <form action="{{ route('register') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">

                        <input type="text" id="name" name="name" placeholder="{{ __('Name') }}"
                            value="{{ old('name') }}"
                            class="w-full p-4 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50 transition duration-200 ease-in-out">
                        @if ($errors->has('name'))
                            @foreach ($errors->get('name') as $item)
                                <span class="text-red-500 text-sm mt-1 block">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="mb-3">

                        <input type="text" id="last_name" name="last_name" placeholder="{{ __('Last Name') }}"
                            value="{{ old('last_name') }}"
                            class="w-full p-4 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50 transition duration-200 ease-in-out">
                        @if ($errors->has('last_name'))
                            @foreach ($errors->get('last_name') as $item)
                                <span class="text-red-500 text-sm mt-1 block">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="mb-3">

                        <input type="email" id="email" name="email" placeholder="{{ __('Email') }}"
                            value="{{ old('email') }}"
                            class="w-full p-4 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50 transition duration-200 ease-in-out">
                        @if ($errors->has('email'))
                            @foreach ($errors->get('email') as $item)
                                <span class="text-red-500 text-sm mt-1 block">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="mb-3">
                        <input type="text" id="birthday" name="birthday" placeholder="{{ __('Birthday') }}"
                            value="{{ old('birthday') }}"
                            class="w-full p-4 border date border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50 transition duration-200 ease-in-out">
                        @if ($errors->has('birthday'))
                            @foreach ($errors->get('birthday') as $item)
                                <span class="text-red-500 text-sm mt-1 block">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="mb-3">
                        <input type="password" id="password" name="password" placeholder="{{ __('Password') }}"
                            class="w-full p-4 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50 transition duration-200 ease-in-out">
                        @if ($errors->has('password'))
                            @foreach ($errors->get('password') as $item)
                                <span class="text-red-500 text-sm mt-1 block">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="mb-3">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            placeholder="{{ __('Password Confirmation') }}"
                            class="w-full p-4 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50 transition duration-200 ease-in-out">
                        @if ($errors->has('password_confirmation'))
                            @foreach ($errors->get('password_confirmation') as $item)
                                <span class="text-red-500 text-sm mt-1 block">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="mb-3 hidden">
                        <input type="text" id="referral_code" name="referral_code"
                            value="{{ request()->referral_code }}" placeholder="{{ __('Referral code') }}"
                            class="w-full p-4 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50 transition duration-200 ease-in-out">
                    </div>

                    <div class="terms flex items-center mb-6">
                        <div class="box mr-2">
                            <input name="accept_terms" id="accept_terms" value="{{ true }}" type="checkbox"
                                class="rounded border-gray-300 text-blue-500 focus:ring-2 focus:ring-blue-400">
                        </div>
                        <div class="box">
                            <label for="accept_terms" class="text-sm text-gray-600">
                                By choosing this option, you accept the <a href="{{ config('system.home_page') }}"
                                    target="_blank" class="text-blue-600 hover:underline">
                                    {{ config('app.name') }}
                                </a>. <a href="{{ config('system.policy_services') }}" target="_blank"
                                    class="text-blue-600 hover:underline">Services Agreement, Privacy Statement</a>, and
                                <a href="{{ config('system.policy_cookies') }}" target="_blank"
                                    class="text-blue-600 hover:underline">Cookies Policy</a>.
                            </label>
                        </div>
                    </div>

                    @if ($errors->has('accept_terms'))
                        @foreach ($errors->get('accept_terms') as $item)
                            <p class="text-red-500 text-sm mb-3">{{ $item }}</p>
                        @endforeach
                    @endif

                    <div class="buttons">
                        <button type="submit"
                            class="w-full bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 ease-in-out">
                            {{ __('Register') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
