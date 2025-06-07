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
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <div class="text-green-500 text-6xl mb-4">
                    <span class="mdi mdi-check-circle-outline"></span>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ __('Payment Successful!') }}</h1>
                <p class="text-gray-600">
                    {{ __('Thank you for your purchase. Your transaction has been completed successfully.') }}</p>
            </div>

            <div class="grid md:grid-cols-2 gap-6 text-sm text-gray-700">
                <!-- User Info -->
                <div>
                    <h2 class="text-lg font-semibold mb-2 border-b pb-1">👤 User Info</h2>
                    <p><span class="font-medium">Name:</span> {{ $transaction['package']['user']['name'] }}
                        {{ $transaction['package']['user']['last_name'] }}</p>
                    <p><span class="font-medium">Email:</span> {{ $transaction['package']['user']['email'] }}</p>
                    <p><span class="font-medium">Verified At:</span> {{ $transaction['package']['user']['verified_at'] }}</p>
                    <p><span class="font-medium">Stripe Customer ID:</span> {{ $transaction['package']['user']['stripe_customer_id'] }}
                    </p>
                </div>

                <!-- Transaction Info -->
                <div>
                    <h2 class="text-lg font-semibold mb-2 border-b pb-1">💳 Transaction Info</h2>
                    <p><span class="font-medium">Code:</span> {{ $transaction['code'] }}</p>
                    <p><span class="font-medium">Status:</span>
                        <span class="text-green-600 font-semibold capitalize">{{ $transaction['status'] }}</span>
                    </p>
                    <p><span class="font-medium">Currency:</span> {{ $transaction['currency'] }}</p>
                    <p><span class="font-medium">Total:</span> ${{ number_format($transaction['total'] / 100, 2) }}</p>
                    <p><span class="font-medium">Recipient:</span> {{ $transaction['recipient'] ?? 'N/A' }}</p>
                    <p><span class="font-medium">Billing Period:</span> {{ ucfirst($transaction['billing_period']) }}</p>
                    <p><span class="font-medium">Payment Method:</span> {{ ucfirst($transaction['payment_method']) }}</p>
                    <p><span class="font-medium">Created At:</span>
                        {{ \Carbon\Carbon::parse($transaction['created_at'])->toDayDateTimeString() }}</p>
                    @if (!empty($transaction['payment_url']))
                        <div
                            class="bg-blue-50 border border-blue-300 text-blue-800 p-4 rounded-md mb-6 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="mdi mdi-receipt text-3xl"></span>
                                <div>
                                    <p class="font-semibold text-base">Payment Receipt Available</p>
                                    <p class="text-sm text-blue-600">You can view and download your receipt for this
                                        transaction.</p>
                                </div>
                            </div>
                            <a href="{{ $transaction['payment_url'] }}" target="_blank"
                                class="inline-flex items-center bg-blue-600 text-white text-sm px-4 py-2 rounded-md hover:bg-blue-700 transition">
                                <span class="mdi mdi-open-in-new mr-2"></span> View Receipt
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Package Info -->
                <div class="md:col-span-2">
                    <h2 class="text-lg font-semibold mb-2 border-b pb-1">📦 Package Info</h2>
                    <p><span class="font-medium">Name:</span> {{ $transaction['package']['meta']['name'] }}</p>
                    <p><span class="font-medium">Description:</span> {!! $transaction['package']['meta']['description'] !!}</p>
                    <p><span class="font-medium">Start At:</span>
                        {{ \Carbon\Carbon::parse($transaction['package']['start_at'])->toDayDateTimeString() }}</p>
                    <p><span class="font-medium">End At:</span>
                        {{ \Carbon\Carbon::parse($transaction['package']['end_at'])->toDayDateTimeString() }}</p>
                    <p><span class="font-medium">Recurring:</span>
                        {{ $transaction['package']['is_recurring'] ? 'Yes' : 'No' }}</p>
                    <p><span class="font-medium">Price:</span>
                        ${{ $transaction['package']['meta']['price']['amount_format'] }}</p>
                </div>
            </div>

            <div class="text-center mt-8">
                <a href="{{ config('system.redirect_to', 'home') }}"
                    class="inline-block bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition">
                    <span class="mdi mdi-view-dashboard-outline mr-1"></span> {{ __('Go to Dashboard') }}
                </a>
            </div>
        </div>
    </div>
@endsection
