@extends('layouts.app')

@section('content')
    <div class="reset mt-5" style="margin: auto">
        <div class="reset-head text-center">
            <h5>{{__('Change Password')}}</h5>
        </div>
        <div class="reset-body">
            <form action="{{ route('password.store') }}" method="post">
                @csrf
                <div class="row">
                    <input type="hidden" class="form-control" name="token" id="token" value="{{ $token }}">
                    <div class="col-12 my-4">
                        <label class="fw-bold" for="email">{{ __('Email') }}</label>
                        <input class="form-control" type="email" name="email" id="email"
                            value="{{ $email }}">
                        @if ($errors->has('email'))
                            @foreach ($errors->get('email') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col-12 my-3">
                        <label class="fw-bold" for="password">{{ __('New Password') }}</label>
                        <input class="form-control" type="password" name="password" id="password"
                            placeholder="{{ __('New Password') }}">
                        @if ($errors->has('password'))
                            @foreach ($errors->get('password') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="col-12 my-3">
                        <label class="fw-bold" for="password">{{ __('Confirm New Password') }}</label>
                        <input class="form-control" type="password" name="password_confirmation" id="password_confirmation"
                            placeholder="{{ __('Confirm New Password') }}">
                    </div>

                    <div class="col-12 mt-5 text-center d-grid">
                        <button class="btn btn-success" type="submit">{{ __('Change Password') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
