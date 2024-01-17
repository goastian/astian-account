@extends('layouts.app')

@section('content')
    @if (session('status'))
        <div class="alert alert-success my-3 text-center">
            {{ session('status') }}
        </div>
    @endif

    <div class="login mt-5" style="margin:auto">
        <form action="{{ route('login') }}" method="post">
            @csrf
            <div class="py-3">
                <div class="login-title h1 fw-bold text-center">
                    {{ __('Login') }} 
                 </div>
                <div class="row mt-5 mb-5">
                    <div class="col-12 mb-3">
                        <label class="fw-bold" for="email">{{ __('Email') }}</label>
                        <input class="form-control" type="email" name="email" placeholder="admin@email.com">
                        @if ($errors->has('email'))
                            @foreach ($errors->get('email') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="col-12 mb-3">
                        <label class="fw-bold" for="password">{{ __('Password') }}</label>
                        <input class="form-control" type="password" name="password" placeholder="{{ __('Password') }}">
                        @if ($errors->has('password'))
                            @foreach ($errors->get('password') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div>

                        @foreach ($query as $item => $value)
                            <input type="hidden" id="{{ $item }}" name="{{ $item }}"
                                value="{{ $value }}">
                        @endforeach

                    </div>
                    <div class="col-12 mt-4 text-center">
                        <div>
                            <button class="btn btn-success mb-2" type="submit">
                                {{ __('Login') }}
                            </button>
                        </div>
                        <div>
                            <a class="btn btn-link my-2 mx-2" href="{{ route('register') }}">
                                {{ __('Register') }}
                            </a>
                        </div>
                        <div>
                            <a class="btn btn-link" style="padding-left: 0"
                                href="/forgot-password">{{ __('Forgot your password?') }}</a>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
