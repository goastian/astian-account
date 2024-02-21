@extends('layouts.app')

@section('content')
    @if (session('status'))
        <div class="alert alert-success my-3 text-center">
            {{ session('status') }}
        </div>
    @endif

    <div class="card login">
        <div class="card-head">
            <p class="h3 fw-bold text-color">{{ config('app.name') }}</p>
        </div>
        <div class="card-body">
            <form action="{{ route('login') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col">
                        <input class="form-control" type="email" name="email" placeholder="{{ __('Email') }}">
                        @if ($errors->has('email'))
                            @foreach ($errors->get('email') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="col">
                        <a class="btn btn-link float-end" style="padding-left: 0"
                            href="/forgot-password">{{ __('Forgot your password?') }}</a>
                        <input class="form-control" type="password" name="password" placeholder="{{ __('Password') }}">
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
                    <div class="col text-center">
                        <div>
                            <button class="btn btn-primary px-5" type="submit">
                                {{ __('Sing in') }}
                            </button>

                            <a class="btn btn-link my-2 mx-2" href="{{ route('register') }}">
                                {{ __("Don't have an account? Sign up.") }}
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
