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
                <div class="card-title text-center">
                    {{ __('Login') }}
                </div>
                <div class="row row-cols-1 col-lg-12">
                    <div class="col my-2">
                        <label for="email">{{ __('Email') }}</label>
                        <input class="form-control" type="email" name="email" placeholder="admin@email.com">
                        @if ($errors->has('email'))
                            @foreach ($errors->get('email') as $item)
                                <span class="error">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="col my-2">
                        <label for="password">{{ __('Password') }}</label>
                        <input class="form-control" type="password" name="password" placeholder="{{ __('Password') }}">
                        @if ($errors->has('password'))
                            @foreach ($errors->get('password') as $item)
                                <span class="error">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="col my-4">

                        <button class="btn btn-block d-block btn-secondary" type="submit" style="width: 40%">
                            {{ __('Login') }}
                        </button>
                        <p>
                            <a class="btn btn-link py-2" style="padding-left: 0"
                                href="/forgot-password">{{ __('Forgot my Password') }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
