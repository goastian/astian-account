@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Recovery my password')])
@endsection

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 px-4">
        <div class="w-full max-w-md bg-white shadow-2xl rounded-2xl p-8">
            <!-- Header -->
            <div class="text-center mb-6">
                <h2 class="text-3xl font-extrabold text-gray-800">
                    {{ __('Request to change password') }}
                </h2>
                <p class="text-sm text-gray-600 mt-2">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </p>
            </div>

            <!-- Form -->
            <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Email Input -->
                <div>
                    <input type="email" name="email" placeholder="{{ __('Email') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @if ($errors->has('email'))
                        @foreach ($errors->get('email') as $item)
                            <span class="text-red-500 text-xs mt-1 block">{{ $item }}</span>
                        @endforeach
                    @endif
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl transition duration-200 font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        {{ __('Send Password Reset Link') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
