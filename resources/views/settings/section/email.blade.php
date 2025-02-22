@extends('settings.index')

@section('form')
    <div class="flex flex-col md:flex-row gap-2 items-start md:space-x-6 p-4 bg-gray-100 rounded-md shadow">

        <div class="w-full md:w-1/4">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ __('Email settings') }}
            </h2>
        </div>


        <div class="w-full md:w-3/4">

            <div class="mb-4 px-2 py-2">
                <label for="verify_account_time" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Account Verification Time (minutes)') }}
                </label>
                <input id="verify_account_time" type="number" name="verify_account_time"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="{{ __('Enter the account verification time in minutes') }}"
                    value="{{ settingItem('verify_account_time') }}">
                <small
                    class="block mt-1 text-gray-600">{{ __('This field specifies the time in minutes for account verification') }}</small>
            </div>


            <div class="mb-4 px-2 py-2">
                <label for="code_2fa_email_expires" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('2FA Email Code Expiration Time (Minutes)') }}
                </label>
                <input id="code_2fa_email_expires" type="number" name="code_2fa_email_expires"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="{{ __('Enter the expiration time for 2FA email code in minutes') }}"
                    value="{{ settingItem('code_2fa_email_expires') }}">
                <small
                    class="block mt-1 text-gray-600">{{ __('This field specifies the expiration time for the 2FA code sent via email, in minutes') }}</small>
            </div>

        </div>
    </div>
@endsection
