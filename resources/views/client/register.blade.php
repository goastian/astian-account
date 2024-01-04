@extends('layouts.app')

@section('content')
    <div class="card w-50 mt-4" style="margin: auto">
        <div class="card-head text-center">
            <h3>{{ __('Midori') }}</h3> 
        </div>
        <div class="card-body mt-4">
            <form action="/register" method="post">
                @csrf
                <div class="row row-cols-2 col-sm-12">
                    <div class="col pb-2">
                        <label for="name">{{ __('Name') }} <span class="text-secondary">{{ __('Required') }}</span></label>
                        <input type="text" id="name" name="name" placeholder="{{ __('Nombre') }}"
                            class="form-control" value="{{ old('name') }}">
                        @if ($errors->has('name'))
                            @foreach ($errors->get('name') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col pb-2">
                        <label for="last_name">{{ __('Last Name') }} <span
                                class="text-secondary">{{ __('Required') }}</span></label>
                        <input type="text" id="last_name" name="last_name" placeholder="{{ __('Apellido') }}"
                            class="form-control" value="{{ old('last_name') }}">
                        @if ($errors->has('last_name'))
                            @foreach ($errors->get('last_name') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col pb-2">
                        <label for="email">{{ __('Email') }} <span class="text-secondary">{{ __('Required') }}</span>
                        </label>
                        <input type="email" id="email" name="email" placeholder="{{ __('Correo Electronico') }}"
                            class="form-control" value="{{ old('email') }}">
                        @if ($errors->has('email'))
                            @foreach ($errors->get('email') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col pb-2">
                        <label for="country">{{ __('Country') }}</label>
                        <input type="text" id="country" name="country" placeholder="{{ __('Pais') }}"
                            class="form-control" value="{{ old('country') }}">
                        @if ($errors->has('country'))
                            @foreach ($errors->get('country') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col pb-2">
                        <label for="city">{{ __('City') }}</label>
                        <input type="text" id="city" name="city" placeholder="{{ __('Ciudad') }}"
                            class="form-control" value="{{ old('city') }}">
                        @if ($errors->has('city'))
                            @foreach ($errors->get('city') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col pb-2">
                        <label for="phone">{{ __('Phone') }}</label>
                        <input type="number" id="phone" name="phone" placeholder="{{ __('Telefono') }}"
                            class="form-control" value="{{ old('phone') }}">
                        @if ($errors->has('phone'))
                            @foreach ($errors->get('phone') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col pb-2">
                        <label for="birthday">{{ __('Birthday') }}</label>
                        <input type="date" id="birthday" name="birthday" placeholder="{{ __('Nacimiento') }}"
                            class="form-control" value="{{ old('birthday') }}">
                        @if ($errors->has('birthday'))
                            @foreach ($errors->get('birthday') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col pb-2">
                        <label for="password">{{ __('Contraseña') }} <span
                                class="text-secondary">{{ __('Required') }}</span></label>
                        <input type="password" id="password" name="password" placeholder="{{ __('Contraseña') }}"
                            class="form-control">
                        @if ($errors->has('password'))
                            @foreach ($errors->get('password') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col pb-2">
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
                    <div class="col-12 text-center mt-5">
                        <button class="btn btn-primary btn-block">{{ __('Registrate') }}</button>

                        <a class="btn btn-warning mx-2" href="{{ route('login') }}">{{ __('Regresar') }}</a>

                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
