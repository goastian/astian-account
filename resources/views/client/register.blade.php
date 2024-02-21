@extends('layouts.app')

@section('content')

    <div class="card register text-color">
        <div class="card-head text-center">
            <h2>Create a new Account</h2>
            <p>Join us and enjoy a new Astian privacy and security.</p>
        </div>
        <div class="card-body">
            <form action="/register" method="post">
                @csrf
                <div class="row">
                    <div class="col">
                        <input type="text" id="name" name="name" placeholder="{{ __('Name') }}"
                            class="form-control" value="{{ old('name') }}">
                        @if ($errors->has('name'))
                            @foreach ($errors->get('name') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col">
                        <input type="text" id="last_name" name="last_name" placeholder="{{ __('Last Name') }}"
                            class="form-control" value="{{ old('last_name') }}">
                        @if ($errors->has('last_name'))
                            @foreach ($errors->get('last_name') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col">
                        <input type="email" id="email" name="email" placeholder="{{ __('Email') }}"
                            class="form-control" value="{{ old('email') }}">
                        @if ($errors->has('email'))
                            @foreach ($errors->get('email') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="col">
                        <label for="birthday">{{ __('Date of birth') }}</label>
                        <input type="date" id="birthday" name="birthday" placeholder="{{ __('Birthday') }}"
                            class="form-control" value="{{ old('birthday') }}">
                        @if ($errors->has('birthday'))
                            @foreach ($errors->get('birthday') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col">
                        <input type="password" id="password" name="password" placeholder="{{ __('Password') }}"
                            class="form-control">
                        @if ($errors->has('password'))
                            @foreach ($errors->get('password') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col">
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
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
