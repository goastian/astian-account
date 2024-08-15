@extends('layouts.pages')

@section('content')
    <div class="form-reset-password">
        <div class="reset-password">
            <div class="head">
                <p>{{ __('Update your password') }}</p>
                <p>{{ __('Dear User in this section you can reset a new password') }}</p>
            </div>
            <div class="body">
                <form action="{{ route('password.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="token" id="token" value="{{ $token }}">

                    <div class="item">
                        <input type="email" name="email" id="email" value="{{ $email }}">
                        @if ($errors->has('email'))
                            @foreach ($errors->get('email') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="item">
                        <input type="password" name="password" id="password" placeholder="{{ __('New Password') }}">
                        @if ($errors->has('password'))
                            @foreach ($errors->get('password') as $item)
                                <span class="errors">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="item">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            placeholder="{{ __('Confirm New Password') }}">
                    </div>

                    <div class="buttons">
                        <button type="submit">{{ __('Change Password') }}</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
