@extends('layouts.app')


@section('content')

    <div class="card forgot-password text-color">
        <div class="card-body">
            <form action="{{ route('password.email') }}" method="post">
                @csrf
                <div class="forgot-body">

                    <div class="forgot-title">
                        <h4 class="text-center fw-bold mb-4">{{ __('Request to change password') }}
                        </h4>
                        <p>
                            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                        </p>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <input type="email" class="form-control" name="email" placeholder="{{ __('Email') }}">
                            @if ($errors->has('email'))
                                @foreach ($errors->get('email') as $item)
                                    <span class="errors">{{ $item }}</span>
                                @endforeach
                            @endif
                        </div>

                        <div class="col-12 mt-4 text-center">
                            <div>
                                <button class="btn btn-success mb-4">{{ __('Send Password Reset Link') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
