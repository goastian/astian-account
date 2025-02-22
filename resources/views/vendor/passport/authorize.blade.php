@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Authorize')])
@endsection

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50">
        <div class="w-full max-w-lg bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ __('Authorization Request') }}</h2>
            <p class="text-gray-700 mb-6">
                <strong>{{ $client->name }}</strong> {{ __('is requesting permission to access your account.') }}
            </p>

            @if (count($scopes) > 0)
                <div class="bg-gray-100 rounded-lg p-4 mb-6">
                    <p class="font-medium text-gray-800">{{ __('This application will be able to:') }}</p>
                    <ul class="list-disc list-inside text-gray-700 mt-2">
                        @foreach ($scopes as $scope)
                            <li>{{ $scope->description }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex space-x-4">
                <form method="post" action="{{ route('passport.authorizations.approve') }}" class="w-full">
                    @csrf
                    <input type="hidden" name="state" value="{{ $request->state }}">
                    <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                    <input type="hidden" name="auth_token" value="{{ $authToken }}">
                    <button type="submit"
                        class="w-full bg-blue-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                        {{ __('Authorize') }}
                    </button>
                </form>

                <form method="post" action="{{ route('passport.authorizations.deny') }}" class="w-full">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="state" value="{{ $request->state }}">
                    <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                    <input type="hidden" name="auth_token" value="{{ $authToken }}">
                    <button type="submit"
                        class="w-full bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg hover:bg-gray-400 transition">
                        {{ __('Cancel') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
