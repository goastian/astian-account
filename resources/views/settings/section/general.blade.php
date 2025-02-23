@extends('settings.index')

@section('form')
    <div class="flex flex-col md:flex-row gap-4 items-start p-4 bg-gray-100 rounded-md shadow">
        <div class="flex-1">
            <h2 class="text-xl font-semibold text-gray-800">{{ __('General settings') }}</h2>
        </div>

        <div class="flex-1 space-y-4">

            <div class="mb-4 px-2 py-2">
                <label for="app_name" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('App name') }}
                </label>
                <input id="app_name" type="text" name="app[name]"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="{{ __('Enter the application name') }}" value="{{ settingItem('app.name') }}">
                <small class="block mt-1 text-gray-600">
                    {{ __('This field specifies the name of the application') }}
                </small>
            </div>


            <div class="mb-4 px-2 py-2">
                <label for="schema_mode" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Schema mode') }}
                </label>
                <select name="schema_mode" id="schema_mode"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="" disabled>{{ __('Choose option') }}</option>
                    <option value="http" {{ settingItem('schema_mode') == 'http' ? 'selected' : '' }}>
                        {{ __('HTTP mode') }}</option>
                    <option value="https" {{ settingItem('schema_mode') == 'https' ? 'selected' : '' }}>
                        {{ __('HTTPS mode') }}</option>
                </select>
                <small
                    class="block mt-1 text-gray-600">{{ __('This option is used to configure the application mode') }}</small>
            </div>

            <div class="mb-4 px-2 py-2">
                <label for="redirect_to" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Redirect to') }}
                </label>
                <input id="redirect_to" type="text" name="redirect_to"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="{{ __('Enter redirect URL') }}" value="{{ settingItem('redirect_to') }}">
                <small
                    class="block mt-1 text-gray-600">{{ __('This field specifies the URL to which the application will redirect') }}</small>
            </div>

            <div class="mb-4 px-2 py-2">
                <label for="home_page" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Home Page URL') }}
                </label>
                <input id="home_page" type="text" name="home_page"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="{{ __('Enter home page URL') }}" value="{{ settingItem('home_page') }}">
                <small
                    class="block mt-1 text-gray-600">{{ __('This field specifies the URL of the home page of the application') }}</small>
            </div>
        </div>
    </div>
@endsection
