@extends('layouts.pages')

@section('content')

    <div class="authorization-card">
        <div class="card-head">
            {{ __('Authorization Request') }}
        </div>
        <div class="card-body">
            <p><strong>{{ $client->name }}</strong> {{ __('is requesting permission to access your account.') }}
            </p>

            <div class="boxes">
                <!-- Scope List -->
                @if (count($scopes) > 0)
                    <div class="scopes">
                        <p><strong>{{ __('This application will be able to:') }}</strong></p>

                        <ul>
                            @foreach ($scopes as $scope)
                                <li>{{ $scope->description }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="boxes">
                <div class="box">
                    <form method="post" action="{{ route('passport.authorizations.approve') }}">
                        @csrf

                        <input type="hidden" name="state" value="{{ $request->state }}">
                        <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                        <input type="hidden" name="auth_token" value="{{ $authToken }}">
                        <button type="submit" class="btn btn-primary">{{ __('Authorize') }}</button>
                    </form>

                </div>
                <div class="box">
                    <form method="post" action="{{ route('passport.authorizations.deny') }}">
                        @csrf
                        @method('DELETE')

                        <input type="hidden" name="state" value="{{ $request->state }}">
                        <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                        <input type="hidden" name="auth_token" value="{{ $authToken }}">
                        <button class="btn btn-secondary">{{ __('Cancel') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
