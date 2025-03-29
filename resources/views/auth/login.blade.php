@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Login')])
@endsection

@section('content')

    <div class="login-form min-h-screen bg-gray-50 flex w-full bg-gray">

        <div class="flex flex-col md:flex-row w-full p-4 md:p-8 gap-8">

            <!-- left  -->
            <div class="login w-full flex items-center justify-center order-2 md:order-1">

                <!-- Login-form -->
                <div class="w-full max-w-md p-8 space-y-8">
                    <div class="head text-start mb-8 flex flex-col gap-8">
                        <p class="text-3xl font-bold text-gray-800 tracking-tight">
                            {{ settingitem('app.name', 'oauth2 server') }}
                        </p>
                        <div class="flex flex-col gap-4">
                            <span class="text-4xl font-bold">Welcome back</span>
                            <p>Access your account quickly and securely to manage your data and settings without complications</p>
                        </div>
                    </div>
                    <div class="body">
                        <form action="{{ route('login') }}" method="post">
                            @csrf

                            <div class="item mb-5">
                                <input type="email" name="email" placeholder="{{ __('email') }}"
                                    class="w-full p-4 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50 transition duration-200 ease-in-out"
                                    required>
                                @if ($errors->has('email'))
                                    @foreach ($errors->get('email') as $item)
                                        <span class="text-red-500 text-sm mt-1 block">{{ $item }}</span>
                                    @endforeach
                                @endif
                            </div>

                            <div class="item mb-6">
                                <input type="password" name="password" placeholder="{{ __('password') }}"
                                    class="w-full p-4 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50 transition duration-200 ease-in-out"
                                    required>
                                @if ($errors->has('password'))
                                    @foreach ($errors->get('password') as $item)
                                        <span class="text-red-500 text-sm mt-1 block">{{ $item }}</span>
                                    @endforeach
                                @endif
                                <a class="text-blue-500 text-sm hover:underline mt-6 block text-right"
                                    href="/forgot-password">{{ __('forgot your password?') }}</a>
                            </div>

                            <!--do not remove this lines-->
                            <div>
                                @foreach ($query as $item => $value)
                                    <input type="hidden" id="{{ $item }}" name="{{ $item }}"
                                        value="{{ $value }}">
                                @endforeach
                            </div>
                            <!--end of the lines-->

                            <div class="buttons mt-6">
                                <button type="submit"
                                    class="w-full green-color-login-card text-white py-3 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 ease-in-out">
                                    {{ __('sign in') }}
                                </button>
                                @if (settingitem('enable_register_member', true))
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

            <!-- Right -->
            <div class="w-full h-ful order-1 md:order-2 py-6 px-6 green-color-login-light rounded-4xl hidden md:block">
                <!-- welcome -->
                <div class="relative w-full h-full rounded-xl overflow-hidden h-[calc(100vh-4rem)] p-6 flex flex-col justify-around">
                    <div class="flex flex-col gap-10 justify-center text-center">
                        <img src="/img/icon.webp" width="35px" />
                        <div class="flex flex-col items-center gap-4">
                            <h2 class="text-2xl">Welcome to Astian</h2>
                            <span class="text-base max-w-100">Access your account and enjoy a secure and personalized experience, with all the tools you need in one place.</span>
                        </div>
                    </div>
                    <!--
                    <div class="green-color-login-card p-7 rounded-2xl flex flex-col gap-4 text-white">
                        <div class="flex gap-4">
                            <img src="" />
                            <div class="flex flex-col">
                                <span>Lauren Weaver</span>
                                <span>Twitter</span>
                            </div>
                        </div>
                        <span>
                            Wonderful color, fantastic paper quality, helpful seller and quick shipping. My poster is just arrived and I love it!!
                        </span>
                        -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
