@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Payment successfully')])
@endsection

@section('header')
    <nav class="bg-indigo-600 text-white py-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center ">
            <a href="{{ config('system.redirect_to', 'home') }}" class="text-lg font-semibold">
                <i class="mdi mdi-home text-2xl"></i>
                {{ __('Dashboard') }}
            </a>
        </div>
    </nav>
@endsection

@section('content')
    <div class="grow-1 mx-4 py-4 container mx-auto my-10 px-4">
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="text-green-500 text-6xl mb-4">
                <i class="mdi mdi-check-circle-outline"></i>
            </div>
            <h1 class="text-3xl font-semibold mb-2">{{ __('Payment Successful!') }}</h1>
            <p class="text-gray-600 mb-6">
                {{ __('Thank you for your purchase. Your transaction has been completed successfully.') }}
            </p>

            <a href="{{ config('system.redirect_to', 'home') }}"
                class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition">
                {{ __('Go to Dashboard') }}
            </a>
        </div>
    </div>
@endsection
