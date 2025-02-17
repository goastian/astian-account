@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Recovery my password')])
@endsection

@section('content')
    <div class="form-forgot-password flex justify-center items-center min-h-screen bg-gray-50">

        <div class="forgot-password bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
            <div class="head text-center mb-6">
                <p class="text-3xl font-semibold text-gray-800">{{ __('Request to change password') }}</p>
                <p class="text-gray-600 mt-2">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </p>
            </div>
            <div class="body">
                <form action="{{ route('password.email') }}" method="post">
                    @csrf

                    <div class="item mb-5">
                        <input type="email"
                            class="w-full p-4 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50 transition duration-200 ease-in-out"
                            name="email" placeholder="{{ __('Email') }}" required>
                        @if ($errors->has('email'))
                            @foreach ($errors->get('email') as $item)
                                <span class="text-red-500 text-sm mt-1 block">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="buttons mt-6">
                        <button type="submit"
                            class="w-full bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 ease-in-out">
                            {{ __('Send Password Reset Link') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
