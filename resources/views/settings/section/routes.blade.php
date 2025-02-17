@extends('settings.index')

@section('form')
    <div class="flex gap-2 items-start space-x-6 p-4 bg-gray-100 rounded-md shadow">
        <div class="w-100">
            <h2 class="text-xl font-semibold text-gray-800">{{ __('Route settings') }}</h2>
        </div>

        <div>
            <div class="mb-4 px-2 py-2">
                <label for="enable_register_member" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Enable Member Registration') }}
                </label>
                <select id="enable_register_member" name="enable_register_member"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="1" {{ settingItem('enable_register_member') == 1 ? 'selected' : '' }}>
                        {{ __('Yes') }}
                    </option>
                    <option value="0" {{ settingItem('enable_register_member') == 0 ? 'selected' : '' }}>
                        {{ __('No') }}
                    </option>
                </select>
                <small class="block mt-1 text-gray-600">
                    {{ __('This option enables or disables member registration') }}
                </small>
            </div>


        </div>
    </div>
@endsection
