@extends('settings.setting')

@section('form')
    <div class="flex gap-2 items-start space-x-6 p-4 bg-gray-100 rounded-md shadow">
        <div class="w-full md:w-1/4">
            <h2 class="text-xl font-semibold text-gray-800">{{ __('Route settings') }}</h2>
        </div>

        <div class="w-full md:w-3/4 space-y-6">
            <div class="mb-4 px-2 py-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Enable user registration') }}
                </label>
                <select name="routes[guest][register]"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="1" {{ config('routes.guest.register') == true ? 'selected' : '' }}>
                        {{ __('Yes') }}
                    </option>
                    <option value="0" {{ config('routes.guest.register') == false ? 'selected' : '' }}>
                        {{ __('No') }}
                    </option>
                </select>
                <small class="block mt-1 text-gray-600">
                    {{ __('Allow visitors to create a new account through the registration page.') }}
                </small>
            </div>


            <div class="mb-4 px-2 py-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Enable developers menu') }}
                </label>
                <select name="routes[users][developers]"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="1" {{ config('routes.users.developers') == true ? 'selected' : '' }}>
                        {{ __('Yes') }}
                    </option>
                    <option value="0" {{ config('routes.users.developers') == false ? 'selected' : '' }}>
                        {{ __('No') }}
                    </option>
                </select>
                <small class="block mt-1 text-gray-600">
                    {{ __('Show or hide the Developers menu in the user dashboard.') }}
                </small>

                <div class="my-3">

                    <div class="mb-4 px-2 py-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Enable api menu') }}
                        </label>
                        <select name="routes[users][api]"
                            class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="1" {{ config('routes.users.api') == true ? 'selected' : '' }}>
                                {{ __('Yes') }}
                            </option>
                            <option value="0" {{ config('routes.users.api') == false ? 'selected' : '' }}>
                                {{ __('No') }}
                            </option>
                        </select>
                        <small class="block mt-1 text-gray-600">
                            {{ __('Show or hide the API access menu for users.') }}
                        </small>
                    </div>

                    <div class="mb-4 px-2 py-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Enable clients menu') }}
                        </label>
                        <select name="routes[users][clients]"
                            class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="1" {{ config('routes.users.clients') == true ? 'selected' : '' }}>
                                {{ __('Yes') }}
                            </option>
                            <option value="0" {{ config('routes.users.clients') == false ? 'selected' : '' }}>
                                {{ __('No') }}
                            </option>
                        </select>
                        <small class="block mt-1 text-gray-600">
                            {{ __('Show or hide the Clients section in the user dashboard.') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
