@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Login')])
@endsection

@section('content')

    <div class="login-form flex justify-center items-center min-h-screen bg-gray-50">

        <div class="box bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
            <div class="login">
                <div class="head text-center mb-8">
                    <p class="text-3xl font-bold text-gray-800 tracking-tight">
                        {{ config('app.name', 'Oauth2 Server') }}
                    </p>
                </div>
                <div class="body">
                    <form action="{{ route('login') }}" method="post">
                        @csrf

                        <div class="item mb-5">
                            <input type="email" name="email" placeholder="{{ __('Email') }}"
                                class="w-full p-4 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50 transition duration-200 ease-in-out"
                                required>
                            @if ($errors->has('email'))
                                @foreach ($errors->get('email') as $item)
                                    <span class="text-red-500 text-sm mt-1 block">{{ $item }}</span>
                                @endforeach
                            @endif
                        </div>

                        <div class="item mb-6">
                            <input type="password" name="password" placeholder="{{ __('Password') }}"
                                class="w-full p-4 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50 transition duration-200 ease-in-out"
                                required>
                            @if ($errors->has('password'))
                                @foreach ($errors->get('password') as $item)
                                    <span class="text-red-500 text-sm mt-1 block">{{ $item }}</span>
                                @endforeach
                            @endif
                            <a class="text-blue-500 text-sm hover:underline mt-2 block text-right"
                                href="{{ route('forgot-password') }}">{{ __('Forgot your password?') }}</a>
                        </div>

                        <!--Do not remove this lines-->
                        <div>
                            @foreach ($query as $item => $value)
                                <input type="hidden" id="{{ $item }}" name="{{ $item }}"
                                    value="{{ $value }}">
                            @endforeach
                        </div>
                        <!--end of the lines-->

                        <div class="buttons mt-6">
                            <button type="submit"
                                class="w-full bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 ease-in-out">
                                {{ __('Sign in') }}
                            </button>
                            @if (config('system.enable_register_member', true))
                                <p class="text-sm text-gray-600 text-center mt-4">
                                    {{ __("Don't have an account?") }}
                                    <a href="{{ route('register') }}" class="text-blue-600 hover:underline">
                                        {{ __('Sign up.') }}
                                    </a>
                                </p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
