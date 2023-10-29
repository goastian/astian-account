@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 mt-5">
                <div class="card card-default" style="width: 70%; margin: auto">
                    <div class="card-header text-center fw-bold">
                        {{ __('Authorization Request') }}
                    </div>
                    <div class="card-body text-center">
                        <!-- Introduction -->
                        <p><strong>{{ $client->name }}</strong> {{ __('is requesting permission to access your account.') }}
                        </p>

                        <!-- Scope List -->
                        @if (count($scopes) > 0)
                            <div class="scopes">
                                <p><strong>{{__("This application will be able to:")}}</strong></p>

                                <ul class="list-group">
                                    @foreach ($scopes as $scope)
                                        <li class="list-group-item">{{ $scope->description }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                            <br>
                        <div class="mt-3 ">

                            <form style="display: inline" method="post"
                                action="{{ route('passport.authorizations.approve') }}">
                                @csrf

                                <input type="hidden" name="state" value="{{ $request->state }}">
                                <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                                <input type="hidden" name="auth_token" value="{{ $authToken }}">
                                <button type="submit" class="btn btn-success btn-approve">{{__("Authorize")}}</button>
                            </form>

                            <!-- Cancel Button -->
                            <form style="display: inline;" method="post"
                                action="{{ route('passport.authorizations.deny') }}">
                                @csrf
                                @method('DELETE')

                                <input type="hidden" name="state" value="{{ $request->state }}">
                                <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                                <input type="hidden" name="auth_token" value="{{ $authToken }}">
                                <button class="btn btn-danger">{{__("Cancel")}}</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
