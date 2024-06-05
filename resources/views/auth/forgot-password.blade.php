@extends('layouts.pages')

@section('content')
    <div class="forgot-password">
        <div class="head">
            <p>{{ __('Request to change password') }}</p>
            <p>
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </p>
        </div>
        <div class="body">
            <form action="{{ route('password.email') }}" method="post">
                @csrf
                <div class="item">

                    <input type="email" class="form-control" name="email" placeholder="{{ __('Email') }}">
                    @if ($errors->has('email'))
                        @foreach ($errors->get('email') as $item)
                            <span class="errors">{{ $item }}</span>
                        @endforeach
                    @endif
                </div>

                <div class="buttons">

                    <button type="submit">{{ __('Send Password Reset Link') }}</button>

                </div>
        </div>
    </div>

@endsection
