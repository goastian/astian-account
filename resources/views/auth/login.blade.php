@extends('layouts.pages')

@section('content')
    @if (session('status'))
        <div class="notify" id="notify">
            <p>
                {{ session('status') }}
            </p>
        </div>
    @endif

    <div class="login-form">

        <div class="box">
            <div class="login">
                <div class="head">
                    <p>
                        {{ config('app.name') }}
                    </p>
                </div>
                <div class="body">
                    <form action="{{ route('login') }}" method="post">
                        @csrf

                        <div class="item">
                            <input type="email" name="email" placeholder="{{ __('Email') }}">
                            @if ($errors->has('email'))
                                @foreach ($errors->get('email') as $item)
                                    <span class="errors">{{ $item }}</span>
                                @endforeach
                            @endif
                        </div>

                        <div class="item">
                            <a class="" href="/forgot-password">{{ __('Forgot your password?') }}</a>
                            <input type="password" name="password" placeholder="{{ __('Password') }}">
                            @if ($errors->has('password'))
                                @foreach ($errors->get('password') as $item)
                                    <span class="errors">{{ $item }}</span>
                                @endforeach
                            @endif
                        </div>
                        <!--Do not remove this lines-->
                        <div>
                            @foreach ($query as $item => $value)
                                <input type="hidden" id="{{ $item }}" name="{{ $item }}"
                                    value="{{ $value }}">
                            @endforeach
                        </div>
                        <!--end of the lines-->
                        <div class="buttons">

                            <button type="submit">
                                {{ __('Sign in') }}
                            </button>

                            <a href="{{ route('register') }}">
                                {{ __("Don't have an account? Sign up.") }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="box">

            <div class="page">
                <div>
                    <h1>
                        {{ config('app.name') }}
                    </h1>
                </div>
                <div class="body">
                    <p>
                        {{ __('A new way to browse the internet.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
