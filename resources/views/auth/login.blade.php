@extends('layouts.pages')

@section('content')
    @if (session('status'))
        <div class="message-status">
            <p>
                {{ session('status') }}
            </p>
        </div>
    @endif

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
                        {{ __('Sing in') }}
                    </button>

                    <a href="{{ route('register') }}">
                        {{ __("Don't have an account? Sign up.") }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
