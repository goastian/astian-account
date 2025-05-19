@extends('settings.setting')

@section('form')
    <div class="flex flex-col md:flex-row gap-4 items-start p-4 bg-gray-100 rounded-md shadow">
        <div class="flex-1">
            <h2 class="text-xl font-semibold text-gray-800">{{ __('General settings') }}</h2>
        </div>

        <div class="w-full md:w-3/4 space-y-4">
            <div class="mb-4 px-2 py-2">
                <label for="app_name" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Organization name') }}
                </label>
                <input id="app_name" type="text" name="app[org_name]"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="{{ __('Enter the organization name') }}" value="{{ config('app.org_name') }}">
                <small class="block mt-1 text-gray-600">
                    {{ __('This field specifies the name of the organization') }}
                </small>
            </div>


            <div class="mb-4 px-2 py-2">
                <label for="app_name" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('App name') }}
                </label>
                <input id="app_name" type="text" name="app[name]"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="{{ __('Enter the application name') }}" value="{{ config('app.name') }}">
                <small class="block mt-1 text-gray-600">
                    {{ __('This field specifies the name of the application') }}
                </small>
            </div>


            <div class="mb-4 px-2 py-2">
                <label for="schema_mode" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Schema mode') }}
                </label>
                <select name="system[schema_mode]"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="" disabled>{{ __('Choose option') }}</option>
                    <option value="http" {{ config('system.schema_mode') == 'http' ? 'selected' : '' }}>
                        {{ __('HTTP mode') }}</option>
                    <option value="https" {{ config('system.schema_mode') == 'https' ? 'selected' : '' }}>
                        {{ __('HTTPS mode') }}</option>
                </select>
                <small
                    class="block mt-1 text-gray-600">{{ __('This option is used to configure the application mode') }}</small>
            </div>

            <div class="mb-4 px-2 py-2">
                <label for="redirect_to" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Redirect to') }}
                </label>
                <input type="text" name="system[redirect_to]"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="{{ __('Enter redirect URL') }}" value="{{ config('system.redirect_to') }}">
                <small
                    class="block mt-1 text-gray-600">{{ __('This field specifies the URL to which the application will redirect') }}</small>
            </div>

            <div class="mb-4 px-2 py-2">
                <label for="home_page" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Home Page URL') }}
                </label>
                <input type="text" name="system[home_page]"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="{{ __('Enter home page URL') }}" value="{{ config('system.home_page') }}">
                <small
                    class="block mt-1 text-gray-600">{{ __('This field specifies the URL of the home page of the application') }}</small>
            </div>

            <div class="mb-4 px-2 py-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Service Agreement URL') }}
                </label>
                <input type="text" name="system[service_agreement]"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    value="{{ config('system.service_agreement') }}">
                <small class="block mt-1 text-gray-600">
                    {{ __('This field specifies the URL where users can read the Service Agreement.') }}
                </small>
            </div>

            <div class="mb-4 px-2 py-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Service Statement URL') }}
                </label>
                <input type="text" name="system[service_statement]"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    value="{{ config('system.service_statement') }}">
                <small class="block mt-1 text-gray-600">
                    {{ __('This field specifies the URL where users can read the Policy Statement.') }}
                </small>
            </div>

            <div class="mb-4 px-2 py-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Policy Cookies URL') }}
                </label>
                <input type="text" name="system[policy_cookies]"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    value="{{ config('system.policy_cookies') }}">
                <small class="block mt-1 text-gray-600">
                    {{ __('This field specifies the URL where users can read the Cookies Policy.') }}
                </small>
            </div>


            <div class="mb-4 px-2 py-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('SCP policies') }}
                </label>
                <select name="system[csp_enabled]"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="" disabled>{{ __('Choose option') }}</option>
                    <option value="{{ true }}" {{ config('system.csp_enabled') == true ? 'selected' : '' }}>
                        {{ __('Yes') }}</option>
                    <option value="{{ false }}" {{ config('system.csp_enabled') == false ? 'selected' : '' }}>
                        {{ __('No') }}</option>
                </select>
                <small
                    class="block mt-1 text-gray-600">{{ __('This option is used to activate the csp policies') }}</small>
            </div>
        </div>
    </div>
@endsection
