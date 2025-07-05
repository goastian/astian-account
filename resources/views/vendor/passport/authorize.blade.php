@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Authorize')])
@endsection

@section('content')
    <div class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
        <div class="w-full max-w-md bg-white rounded-xl shadow-md p-8 space-y-6 border border-gray-200">
            <div class="space-y-1">
                <h1 class="text-xl font-semibold text-gray-900">{{ __('Authorization Request') }}</h1>
                <p class="text-gray-700">
                    <span class="font-medium">{{ $client->name }}</span>
                    {{ __('is requesting access to your account.') }}
                </p>
            </div>

            @if (count($scopes) > 0)
                <div class="bg-gray-100 rounded-md p-4">
                    <p class="text-sm font-medium text-gray-800 mb-2">{{ __('This application will be able to:') }}</p>
                    <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
                        @foreach ($scopes as $scope)
                            <li>{{ $scope->description }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-3">
                <form method="POST" action="{{ route('passport.authorizations.approve') . '?nonce=' . $request->nonce }}">
                    @csrf
                    <input type="hidden" name="state" value="{{ $request->state }}">
                    <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                    <input type="hidden" name="auth_token" value="{{ $authToken }}">

                    <button type="submit"
                        class="w-full bg-blue-600 text-white text-sm font-medium py-2 rounded-md hover:bg-blue-700 transition">
                        {{ __('Authorize') }}
                    </button>
                </form>

                <form method="POST" action="{{ route('passport.authorizations.deny') }}">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="state" value="{{ $request->state }}">
                    <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                    <input type="hidden" name="auth_token" value="{{ $authToken }}">

                    <button type="submit"
                        class="w-full bg-gray-200 text-gray-800 text-sm font-medium py-2 rounded-md hover:bg-gray-300 transition">
                        {{ __('Cancel') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
