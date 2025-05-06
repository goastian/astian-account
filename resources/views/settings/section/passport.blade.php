@extends('settings.setting')

@section('form')
    <div class="flex flex-col md:flex-row gap-2 items-start md:space-x-6 p-4 bg-gray-100 rounded-md shadow">

        <div class="w-full md:w-1/4">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ __('Passport settings') }}
            </h2>
        </div>

        <div class="w-full md:w-3/4">
            <div class="mb-4 px-2 py-2">
                <label for="cookie_name" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Cookie Name') }}
                </label>
                <input id="cookie_name" type="text" name="system[cookie_name]"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="{{ __('Enter the cookie name for Passport') }}" value="{{ config('system.cookie_name') }}">
                <small
                    class="block mt-1 text-gray-600">{{ __('This field specifies the name of the cookie used by Laravel Passport for authentication') }}</small>
            </div>

            <div class="mb-4 px-2 py-2">
                <label for="passport_token_services" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Passport Token Services Cookie Name') }}
                </label>
                <input type="text" name="system[passport_token_services]"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="{{ __('Enter the cookie name for Passport token services') }}"
                    value="{{ config('system.passport_token_services') }}">
                <small
                    class="block mt-1 text-gray-600">{{ __('This field specifies the name of the cookie used by Laravel Passport for authentication in microservices') }}</small>
            </div>
        </div>
    </div>
@endsection
