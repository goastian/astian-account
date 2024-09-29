@extends('layouts.pages')

@section('content')
    <div class="form-factor">
        <div class="factor-2fa">
            <div class="head">
                <p>{{ __('verify my account') }}</p>
                <p>
                    {{ __('please check your email address and type your code in the field below.') }}
                </p>

            </div>
            <div class="body">
                <form action="{{ route('factor.email.login') }}" method="post">
                    <div class="item">
                        @csrf
                        <input type="hidden" id="email" name="email" value="{{ session('email') ?: old('email') }}">
                        <input type="text" id="token" name="token">
                    </div>
                    @if ($errors->has('token'))
                        <span class="errors">
                            {{ $errors->first('token') }}
                        </span>
                    @endif
                    <div class="buttons">
                        <button>{{ __('Check my account') }}</button>
                    </div>

                    <div class="item">
                        @if (session('warning'))
                            {{ session('warning') }}
                        @endif
                    </div>

                    @foreach ($query as $item => $value)
                        <input type="hidden" id="{{ $item }}" name="{{ $item }}"
                            value="{{ $value }}">
                    @endforeach

                    <div class="item">
                        <a href="{{ route('login') }}">
                            {{ __('Is your code expired? Please try again.') }}
                        </a>
                    </div>

                </form>
            </div>
            <div class="foot">
                @if (session('status'))
                    <div class="message-status ">
                        <p>
                            {{ session('status') }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
