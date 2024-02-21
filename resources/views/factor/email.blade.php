@extends('layouts.app')

@section('content')
    <div class="card factor text-color">
        <div class="card-head">
            <h3>
                {{ __('Two Factor Authentication') }}
            </h3>
            <p>
                {{ __('We sent a verification code to your email. Enter the code from the email in the field below.') }}
            </p>
        </div>
        <div class="card-body">
            <form style="display: inline-block" action="{{ route('factor.email.login') }}" method="post">
                <div class="row">
                    <div class="col pt-4">
                        @csrf
                        <input type="hidden" id="email" name="email" value="{{ session('email') ?: old('email') }}">
                        <input type="text" id="token" name="token" class="form-control w-75 d-inline"
                            style="margin: auto">
                    </div>
                    <div class="col-12 mt-3 text-sm errors">
                        @if ($errors->has('token'))
                            {{ $errors->first('token') }}
                        @endif
                    </div>
                    <div class="col mt-3">
                        <button class="btn btn-primary">{{ __('Verify my account') }}</button>
                    </div>
                </div>

                <div class="col mt-3 text-sm errors">
                    @if (session('warning'))
                        {{ session('warning') }}
                    @endif
                </div>

                <div>
                    @foreach ($query as $item => $value)
                        <input type="hidden" id="{{ $item }}" name="{{ $item }}"
                            value="{{ $value }}">
                    @endforeach
                </div>

                <a class="btn btn-link" href="{{ route('login') }}">
                    {{ __('The code has expired, please try again!') }}
                </a>
            </form>

        </div>
        @if (session('status'))
            <div class="text-white bg-secondary">
                {{ session('status') }}
            </div>
        @endif
    </div>
@endsection
