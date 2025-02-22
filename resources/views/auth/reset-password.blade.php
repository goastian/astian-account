@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Reset my password')])
@endsection

@section('content')
    <div class="form-reset-password flex justify-center items-center min-h-screen bg-gray-50">

        <div class="reset-password bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
            <div class="head text-center mb-6">
                <p class="text-3xl font-semibold text-gray-800">{{ __('Update your password') }}</p>
                <p class="text-gray-600 mt-2">
                    {{ __('Dear User in this section you can reset a new password') }}
                </p>
            </div>
            <div class="body">
                <form action="{{ route('password.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="token" id="token" value="{{ $token }}">

                    <div class="item mb-5">
                        <input type="email" name="email" id="email" value="{{ $email }}"
                            class="w-full p-4 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50 transition duration-200 ease-in-out"
                            required>
                        @if ($errors->has('email'))
                            @foreach ($errors->get('email') as $item)
                                <span class="text-red-500 text-sm mt-1 block">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="item mb-5">
                        <input type="password" name="password" id="password" placeholder="{{ __('New Password') }}"
                            class="w-full p-4 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50 transition duration-200 ease-in-out"
                            required>
                        @if ($errors->has('password'))
                            @foreach ($errors->get('password') as $item)
                                <span class="text-red-500 text-sm mt-1 block">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="item mb-5">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            placeholder="{{ __('Confirm New Password') }}"
                            class="w-full p-4 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50 transition duration-200 ease-in-out"
                            required>
                    </div>

                    <div class="buttons mt-6">
                        <button type="submit"
                            class="w-full bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 ease-in-out">
                            {{ __('Change Password') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
