@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Account verified')])
@endsection

@push('css')
    <style>
        @keyframes fade-in-up {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.5s ease-out;
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 transform transition-all animate-fade-in-up">
            <div class="text-center">
                <div class="mb-4 animate-bounce">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100">
                        <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                    {{ __('Account Verified Successfully!') }}
                </h2>
                <p class="text-gray-600 mb-6">
                    {{ __('Thank you for verifying your email address. Your account is now fully activated.') }}
                </p>
                <div class="space-y-3">
                    <a href="{{ config('system.redirect_to') }}"
                        class="block w-full px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition duration-150">
                        {{ __('Continue to Dashboard') }}
                    </a>
                    <p class="text-sm text-gray-500 mb-4">
                        {{ __('Redirecting in') }} <span id="counter">5 {{ __('seconds') }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        const route = "{{ config('system.redirect_to', 'about') }}"
        let timeLeft = 10;
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('counter').innerHTML = timeLeft + ' seconds';
            let downloadTimer = setInterval(function() {
                timeLeft -= 1;
                if (timeLeft <= 0) {
                    clearInterval(downloadTimer);
                    window.location.href = route;
                } else {
                    document.getElementById('counter').innerHTML = timeLeft + ' seconds';
                }
            }, 1000);
        })
    </script>
@endpush
