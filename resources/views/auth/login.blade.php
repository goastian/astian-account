@extends('layouts.app')

@section('content')
    @if (session('status'))
        <div class="alert alert-success my-3 text-center">
            {{ session('status') }}
        </div>
    @endif

    <div class="card mt-5" style="width: 40%; margin:auto">
        <form action="{{ route('login') }}" method="post">
            @csrf
            <div class="card-body">
                <div class="card-title fw-bold text-center">
                    {{ __('Login') }}
                </div>
                <div class="row row-cols-1 col-lg-12">
                    <div class="col my-2">
                        <label class="fw-bold" for="email">{{ __('Email') }}</label>
                        <input class="form-control" type="email" name="email" placeholder="admin@email.com">
                        @if ($errors->has('email'))
                            @foreach ($errors->get('email') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="col my-2">
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
                    <div class="col my-4 text-center">

                        <button class="btn  btn-primary" type="submit" style="width: 40%">
                            {{ __('Login') }}
                        </button>

                        <a class="btn btn-success mx-2" href="{{ route('register')}}" style="width: 40%">
                            {{ __('Register') }}
                        </a>
                        <p>
                            <a class="btn btn-link pt-4" style="padding-left: 0"
                                href="/forgot-password">{{ __('Forgot your password?') }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
