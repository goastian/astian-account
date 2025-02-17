@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Redirecting to the Module')])
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
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100">
                        <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                    Redirecting to the Module...
                </h2>
                <p class="text-gray-600 mb-6">
                    You are being redirected. Please wait a moment.
                </p>
                <div class="space-y-3">
                    <a href="{{ $redirect_to }}"
                        class="block w-full px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition duration-150">
                        Go Now
                    </a>
                    <p class="text-sm text-gray-500 mb-4">
                        Redirecting in <span id="counter">3 seconds</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        const route = "{{ $redirect_to }}";
        let timeLeft = 5;
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('counter').innerHTML = timeLeft + ' seconds';
            let countdown = setInterval(function() {
                timeLeft -= 1;
                if (timeLeft <= 0) {
                    clearInterval(countdown);
                    window.location.href = route;
                } else {
                    document.getElementById('counter').innerHTML = timeLeft + ' seconds';
                }
            }, 1000);
        });
    </script>
@endpush
