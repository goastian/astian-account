@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Verify My Account')])
@endsection

@section('content')
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
        <div class="max-w-md w-full bg-white shadow-lg rounded-lg p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4 text-center">
                {{ __('Account Not Verified') }}
            </h1>
            <p class="text-gray-600 text-center mb-6">
                {{ __('It seems your account is not yet verified. Please check your email for the verification link. If you didn’t receive the email, you can request a new one below.') }}
            </p>
            <form method="POST" action="{{ route('users.verification.email') }}">
                @csrf
                <button type="submit"
                    class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300">
                    {{ __('Resend Verification Email') }}
                </button>
            </form>
            <p class="text-sm text-gray-500 mt-4 text-center">
                {{ __('If you’ve already verified your account, please try logging in again.') }}
            </p>
        </div>
    </div>
@endsection
