@extends('layouts.app')

@section('content')
    <div class="card mt-5" style="margin: auto">
        <div class="card-body">
            <div class="card-title text-center">
                <h5>Camibar la contraseña</h5>
            </div>
            <form action="{{ route('password.store') }}" method="post">
                @csrf
                <div class="col row-cols-1 col-lg-12">
                    <input type="hidden"  class="form-control" name="token" id="token" value="{{ $token }}">
                    <div class="col my-4">
                        <label for="email">{{ __('Email') }}</label>
                        <input  class="form-control" type="email" name="email" id="email" value="{{ $email }}" >
                           @if ($errors->has('email'))
                            @foreach ($errors->get('email') as $item)
                                <span class="error">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col my-3">
                        <label for="password">{{ __('New Password') }}</label>
                        <input class="form-control" type="password" name="password" id="password" placeholder="{{ __('New Password') }}">
                        @if ($errors->has('password'))
                            @foreach ($errors->get('password') as $item)
                                <span class="error">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>

                     <div class="col my-3">
                        <label for="password">{{ __('Confirm New Password') }}</label>
                        <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" placeholder="{{ __('Confirm New Password') }}">
                    </div>

                    <div class="col mt-5 text-center">
                        <button class="btn btn-success" type="submit">{{ __('Change Password') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection