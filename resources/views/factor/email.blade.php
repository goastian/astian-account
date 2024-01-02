@extends('layouts.app')

@section('content')
    <div class="mt-5 w-75 text-center" style="margin: auto">
        <div class="card w-50 bg-dark text-light" style="margin: auto">

            <div class="card-head fw-bold text-uppercase mb-5">
                <h4>
                    {{ __('Two Factor Authentication') }}
                </h4>
            </div>
            <div class="body mb-5">
                <div class="mb-3 text-uppercase">
                    <label for="token">{{ __('Please type your code 2FA') }}</label>
                </div>
                <div class="mb-4">
                    <form style="display: inline-block" action="{{ route('factor.email.login') }}" method="post">
                        <div class="row row-cols-sm-2 col-12 pb-3 border-bottom">
                            <div class="col">
                                @csrf
                                <input type="hidden" id="email" name="email"
                                    value="{{ session('email') ?: old('email') }}">
                                <input type="text" id="token" name="token" class="form-control d-inline"
                                    style="margin: auto">
                            </div>
                            <div class="col">
                                <button class="btn d-inline mx-0 mx-0 btn-success">{{ __('Login') }}</button>
                            </div>
                        </div>

                        <div class="col mt-3 text-sm errors">
                            @if (session('warning'))
                                {{ session('warning') }}
                            @endif
                        </div>

                        <div class="col-12 text-sm errors">
                            @if ($errors->has('token'))
                                {{ $errors->first('token') }}
                            @endif
                        </div>
                        <div>
                            @foreach ($query as $item => $value)
                                <input type="hidden" id="{{ $item }}" name="{{ $item }}"
                                    value="{{ $value }}">
                            @endforeach
                        </div>
                    </form>

                    <div class="text-justify mt-3">
                        <a class="btn btn-link btn-block" href="{{ route('login') }}">
                            {{ __('El codigo ha caducado, por favor intentalo otra vez!') }}
                        </a>
                    </div>

                </div>
            </div>

        </div>
        @if (session('status'))
            <div class="text-white bg-secondary">
                {{ session('status') }}
            </div>
        @endif
    </div>
    </div>
@endsection
