@extends('layouts.app')


@section('content')
    <div class="forgot mt-5" style="margin:auto">
        <form action="{{ route('password.email') }}" method="post">
            @csrf
            <div class="forgot-body">
                <div class="forgot-title">
                    <h4 class="text-center fw-bold mb-4">{{ __('Request to change password') }}</h4
                        class="text-center fw-bold mb-4">
                    <p>
                        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                    </p>
                </div>
                <div class="row row-cols-1 col-lg-12">
                    <div class="col">
                        <label for="email" class="fw-bold">{{ __('Email') }}</label>
                        <input type="email" class="form-control" name="email" placeholder="admin@email.com">
                        @if ($errors->has('email'))
                            @foreach ($errors->get('email') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="col mt-4 text-center">
                        <div>
                            <button class="btn btn-secondary mb-4">{{ __('Send Password Reset Link') }}</button>
                        </div>
                        <div>
                            <a class="btn btn-link mx-2" href="{{ route('login') }}">{{ __('Go back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
