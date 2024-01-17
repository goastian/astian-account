@extends('layouts.app')

@section('content')
    <div class="register mt-4" style="margin: auto">
        <div class="register-title text-center">
            <h3>{{ __('Midori') }}</h3>
        </div>
        <div class="register-body mt-4">
            <form action="/register" method="post">
                @csrf
                <div class="row">
                    <div class="col pb-4">
                        <label for="name">{{ __('Name') }} <span
                                class="text-secondary">{{ __('Required') }}</span></label>
                        <input type="text" id="name" name="name" placeholder="{{ __('Name') }}"
                            class="form-control" value="{{ old('name') }}">
                        @if ($errors->has('name'))
                            @foreach ($errors->get('name') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col pb-4">
                        <label for="last_name">{{ __('Last Name') }} <span
                                class="text-secondary">{{ __('Required') }}</span></label>
                        <input type="text" id="last_name" name="last_name" placeholder="{{ __('Last Name') }}"
                            class="form-control" value="{{ old('last_name') }}">
                        @if ($errors->has('last_name'))
                            @foreach ($errors->get('last_name') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col pb-4">
                        <label for="email">{{ __('Email') }} <span class="text-secondary">{{ __('Required') }}</span>
                        </label>
                        <input type="email" id="email" name="email" placeholder="{{ __('Email') }}"
                            class="form-control" value="{{ old('email') }}">
                        @if ($errors->has('email'))
                            @foreach ($errors->get('email') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col pb-4">
                        <label for="country">{{ __('Country') }}</label>
                        <input type="text" id="country" name="country" placeholder="{{ __('Country') }}"
                            class="form-control" value="{{ old('country') }}">
                        @if ($errors->has('country'))
                            @foreach ($errors->get('country') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col pb-4">
                        <label for="city">{{ __('City') }}</label>
                        <input type="text" id="city" name="city" placeholder="{{ __('City') }}"
                            class="form-control" value="{{ old('city') }}">
                        @if ($errors->has('city'))
                            @foreach ($errors->get('city') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col pb-4">
                        <label for="phone">{{ __('Phone') }}</label>
                        <input type="number" id="phone" name="phone" placeholder="{{ __('Phone') }}"
                            class="form-control" value="{{ old('phone') }}">
                        @if ($errors->has('phone'))
                            @foreach ($errors->get('phone') as $item)
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
                        <label for="password">{{ __('Password') }} <span
                                class="text-secondary">{{ __('Required') }}</span></label>
                        <input type="password" id="password" name="password" placeholder="{{ __('Contraseña') }}"
                            class="form-control">
                        @if ($errors->has('password'))
                            @foreach ($errors->get('password') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col pb-4">
                        <label for="password_confirmation">{{ __('Password Confirmation') }} <span
                                class="text-secondary">{{ __('Required') }}</span></label>
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
                            <button class="btn btn-warning mb-3">{{ __('Register') }}</button>

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
