@extends('layouts.app')

@section('content')
    <div class="register">
        <div class="register-head h1 pt-2">
            <img src="images/fuentes.svg" alt="astian-fuente" class="astian-fuentes">
            <img src="favicon.svg" alt="astian-logo" class="astian-logo">
        </div>
        <div class="register-title text-center fw-bold mt-3">
            {{ __('Register') }} 
        </div>
        <div class="register-body mt-4">
            <form action="/register" method="post">
                @csrf
                <div class="row">
                    <div class="col pb-4">
                        <input type="text" id="name" name="name" placeholder="{{ __('Name') }}"
                            class="form-control" value="{{ old('name') }}">
                        @if ($errors->has('name'))
                            @foreach ($errors->get('name') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col pb-4">
                        <input type="text" id="last_name" name="last_name" placeholder="{{ __('Last Name') }}"
                            class="form-control" value="{{ old('last_name') }}">
                        @if ($errors->has('last_name'))
                            @foreach ($errors->get('last_name') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col pb-4">
                        <input type="email" id="email" name="email" placeholder="{{ __('Email') }}"
                            class="form-control" value="{{ old('email') }}">
                        @if ($errors->has('email'))
                            @foreach ($errors->get('email') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                   
                    <div class="col pb-4">
                        <label for="birthday">{{ __('Birthday') }}</label>
                        <input type="date" id="birthday" name="birthday" placeholder="{{ __('Birthday') }}"
                            class="form-control" value="{{ old('birthday') }}">
                        @if ($errors->has('birthday'))
                            @foreach ($errors->get('birthday') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col-12 pt-4 h4 border-bottom">
                    </div>
                    <div class="col pb-4">
                        <input type="password" id="password" name="password" placeholder="{{ __('Contraseña') }}"
                            class="form-control">
                        @if ($errors->has('password'))
                            @foreach ($errors->get('password') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col pb-4">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            placeholder="{{ __('Password Confirmation') }}" class="form-control">
                        @if ($errors->has('password_confirmation'))
                            @foreach ($errors->get('password_confirmation') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col-12 mt-3 text-center">
                        <div>
                            <button class="btn btn-primary bg-primary mb-3">{{ __('Register') }}</button>

                        </div>
                        <div>
                            <a class="btn btn-link mx-2" href="{{ route('login') }}">{{ __('Go back') }}</a>
                        </div>

                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
