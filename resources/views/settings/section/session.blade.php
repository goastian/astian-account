@extends('settings.setting')

@section('form')
    <div class="flex flex-col md:flex-row gap-4 items-start p-6 bg-gray-50 rounded-lg shadow-sm border border-gray-200">

        <div class="w-full md:w-1/4">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ __('Session settings') }}
            </h2>
        </div>

        <div class="w-full md:w-3/4 space-y-4">

            <div class="p-4 border border-blue-300 rounded-lg bg-blue-50 shadow-sm">
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Session Lifetime') }}</label>
                    <input type="number" name="session[lifetime]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300"
                        value="{{ config('session.lifetime') }}">
                    <small
                        class="text-gray-500">{{ __('Here you may specify the number of minutes that you wish the session to be allowed to remain idle before it expires') }}</small>
                </div>
            </div>

            <div class="p-4 border border-blue-300 rounded-lg bg-blue-50 shadow-sm">
                <label for="">Expire on close</label>
                <select name="session[expire_on_close]"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="" disabled>{{ __('Choose option') }}</option>
                    <option value="{{ true }}" {{ config('session.expire_on_close') == true ? 'selected' : '' }}>
                        {{ __('Yes') }}
                    </option>
                    <option value="{{ false }}" {{ config('session.expire_on_close') == false ? 'selected' : '' }}>
                        {{ __('No') }}
                    </option>
                </select>
                <small class="text-gray-500">
                    {{ __('If you want them to immediately expire on the browser closing, set that option.') }}
                </small>
            </div>

            <div class="p-4 border border-blue-300 rounded-lg bg-blue-50 shadow-sm">
                <label for="">{{ __('Session Encryption') }}</label>
                <select name="session[encrypt]"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="" disabled>{{ __('Choose option') }}</option>
                    <option value="{{ true }}" {{ config('session.encrypt') == true ? 'selected' : '' }}>
                        {{ __('Yes') }}
                    </option>
                    <option value="{{ false }}" {{ config('session.encrypt') == false ? 'selected' : '' }}>
                        {{ __('No') }}
                    </option>
                </select>
                <small
                    class="text-gray-500">{{ __('This option allows you to easily specify that all of your session data should be encrypted before it is stored.') }}</small>
            </div>

            <div class="p-4 border border-blue-300 rounded-lg bg-blue-50 shadow-sm">
                <h1 class="mb-2">{{ __('Cookie settings') }}</h1>
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Session cookie name') }}</label>
                    <input type="text" name="session[cookie]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300"
                        value="{{ config('session.cookie') }}">
                    <small class="text-gray-500">
                        {{ __('Here you may change the name of the cookie used to identify a session instance by ID') }}
                    </small>
                </div>

                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Session cookie csrf name') }}</label>
                    <input type="text" name="session[xcsrf-token]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300"
                        value="{{ config('session.xcsrf-token') }}">
                    <small class="text-gray-500">
                        {{ __('Here you may change the name of the cookie used to identify a session instance by ID') }}
                    </small>
                </div>

                <div class="mb-2">
                    <label for="">HTTPS Only Cookies</label>
                    <select name="session[secure]"
                        class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="" disabled>{{ __('Choose option') }}</option>
                        <option value="{{ true }}" {{ config('session.secure') == true ? 'selected' : '' }}>
                            {{ __('Yes') }}
                        </option>
                        <option value="{{ false }}" {{ config('session.secure') == false ? 'selected' : '' }}>
                            {{ __('No') }}
                        </option>
                    </select>
                    <small class="text-gray-500">
                        {{ __('By setting this option to true, session cookies will only be sent back to the server if the browser has a HTTPS connection') }}
                    </small>
                </div>

                <div class="mb-2">
                    <label for="">HTTP Access Only</label>
                    <select name="session[http_only]"
                        class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="" disabled>{{ __('Choose option') }}</option>
                        <option value="{{ true }}" {{ config('session.http_only') == true ? 'selected' : '' }}>
                            {{ __('Yes') }}
                        </option>
                        <option value="{{ false }}" {{ config('session.http_only') == false ? 'selected' : '' }}>
                            {{ __('No') }}
                        </option>
                    </select>
                    <small class="text-gray-500">
                        {{ __('Setting this value to true will prevent JavaScript from accessing the value of the cookie and the cookie will only be accessible through the HTTP protocol') }}
                    </small>
                </div>

                <div class="mb-2">
                    <label for="">Partitioned Cookies</label>
                    <select name="session[http_only]"
                        class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="" disabled>{{ __('Choose option') }}</option>
                        <option value="{{ true }}" {{ config('session.http_only') == true ? 'selected' : '' }}>
                            {{ __('Yes') }}
                        </option>
                        <option value="{{ false }}" {{ config('session.http_only') == false ? 'selected' : '' }}>
                            {{ __('No') }}
                        </option>
                    </select>
                    <small class="text-gray-500">
                        {{ __('Setting this value to true will tie the cookie to the top-level site for a cross-site context') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
@endsection
