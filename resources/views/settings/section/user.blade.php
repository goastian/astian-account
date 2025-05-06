@extends('settings.setting')

@section('form')
    <div class="flex flex-col md:flex-row gap-2 items-start md:space-x-6 p-4 bg-gray-100 rounded-md shadow">

        <div class="w-full md:w-1/4">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ __('User settings') }}
            </h2>
        </div>

        <div class="w-full md:w-3/4">
            <div class="mb-4 px-2 py-2">
                <label for="disable_create_user_by_command" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Disable User Creation by Command') }}
                </label>
                <select id="disable_create_user_by_command" name="system[disable_create_user_by_command]"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="1" {{ config('system.disable_create_user_by_command') == 1 ? 'selected' : '' }}>
                        {{ __('Yes') }}
                    </option>
                    <option value="0" {{ config('system.disable_create_user_by_command') == 0 ? 'selected' : '' }}>
                        {{ __('No') }}
                    </option>
                </select>
                <small class="block mt-1 text-gray-600">
                    {{ __('This option disables the ability to create users through a command') }}
                </small>
            </div>

            <div class="mb-4 px-2 py-2">
                <label for="destroy_user_after" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Destroy Users After (Days)') }}
                </label>
                <input id="destroy_user_after" type="number" name="system[destroy_user_after]"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="{{ __('Enter the number of days after which users will be destroyed') }}"
                    value="{{ config('system.destroy_user_after') }}">
                <small
                    class="block mt-1 text-gray-600">{{ __('This field specifies the number of days after which the users will be destroyed') }}</small>
            </div>

        </div>
    </div>
@endsection
