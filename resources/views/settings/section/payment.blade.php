@extends('settings.setting')

@section('form')
    <div class="flex flex-col md:flex-row gap-4 items-start p-6 bg-gray-50 rounded-lg shadow-sm border border-gray-200">

        <div class="w-full md:w-1/4">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ __('Payment settings') }}
            </h2>
        </div>
        @php
            $payment = billing_methods();
        @endphp
        <div class="w-full md:w-3/4 space-y-4">

            <div class="p-4 border border-blue-300 rounded-lg bg-blue-50 shadow-sm">
                <h3 class="text-lg font-semibold text-blue-700 mb-2 border-b pb-2">
                    Renew
                </h3>

                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Enable Renewals') }}</label>
                    <select name="billing[renew][enable]"
                        class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="" disabled>{{ __('Choose an option') }}</option>
                        <option value="{{ true }}" {{ config('billing.renew.enable') == true ? 'selected' : '' }}>
                            {{ __('Yes') }}</option>
                        <option value="{{ false }}" {{ config('billing.renew.enable') == false ? 'selected' : '' }}>
                            {{ __('No') }}</option>
                    </select>
                    <small class="text-gray-600">
                        Enable the automatic renewal system. Manual (offline) payments are excluded from this automation.
                    </small>
                </div>

                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Hours Before Expiry') }}</label>
                    <input type="text" name="billing[renew][hours_before]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300"
                        value="{{ config('billing.renew.hours_before') }}">
                    <small class="text-gray-600">
                        Defines how many hours before the expiration date the renewal process should be executed.
                    </small>
                </div>

                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Enable Bonus on Renewal') }}</label>
                    <select name="billing[renew][bonus_enabled]"
                        class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="" disabled>{{ __('Choose an option') }}</option>
                        <option value="{{ true }}"
                            {{ config('billing.renew.bonus_enabled') == true ? 'selected' : '' }}>
                            {{ __('Yes') }}</option>
                        <option value="{{ false }}"
                            {{ config('billing.renew.bonus_enabled') == false ? 'selected' : '' }}>
                            {{ __('No') }}</option>
                    </select>
                    <small class="text-gray-600">
                        If enabled, the bonus duration defined in the plan will also be applied during renewals.
                    </small>
                </div>

                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Grace Period (Days)') }}</label>
                    <input type="text" name="billing[renew][grace_period_days]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300"
                        value="{{ config('billing.renew.grace_period_days') }}">
                    <small class="text-gray-600">
                        Additional number of days after the expiration date during which the package can still be renewed.
                    </small>
                </div>
            </div>


            <div class="p-4 border border-blue-300 rounded-lg bg-blue-50 shadow-sm">
                <h3 class="text-lg font-semibold text-blue-700 mb-2 border-b pb-2">
                    <span class="mdi {{ config('billing.methods.stripe.icon') }} text-2xl"></span>
                    Stripe
                </h3>
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                    <input type="text" name="billing[methods][stripe][name]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300"
                        value="{{ config('billing.methods.stripe.name') }}">
                </div>

                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Icon') }}</label>
                    <input type="text" name="billing[methods][stripe][icon]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300"
                        value="{{ config('billing.methods.stripe.icon') }}">
                </div>

                <div class="mb-2">
                    <label for="">Enabled</label>
                    <select name="billing[methods][stripe][enable]"
                        class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="" disabled>{{ __('Choose option') }}</option>
                        <option value="{{ true }}"
                            {{ config('billing.methods.stripe.enable') == true ? 'selected' : '' }}>
                            {{ __('Yes') }}</option>
                        <option value="{{ false }}"
                            {{ config('billing.methods.stripe.enable') == false ? 'selected' : '' }}>
                            {{ __('No') }}</option>
                    </select>
                </div>

                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Secret') }}</label>
                    <input type="text" name="services[stripe][secret]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300"
                        value="{{ config('services.stripe.secret') }}">
                </div>

                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Key') }}</label>
                    <input type="text" name="services[stripe][key]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300"
                        value="{{ config('services.stripe.key') }}">
                </div>

                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Webhook secret') }}</label>
                    <input type="text" name="services[stripe][webhook_secret]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300"
                        value="{{ config('services.stripe.webhook_secret') }}">
                </div>
            </div>

            <div class="p-4 border border-blue-300 rounded-lg bg-blue-50 shadow-sm">
                <h3 class="text-lg font-semibold text-blue-700 mb-2 border-b pb-2">
                    <span class="mdi {{ config('billing.methods.offline.icon') }} text-2xl"></span>
                    Offline
                </h3>
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                    <input type="text" name="billing[methods][offline][name]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300"
                        value="{{ config('billing.methods.offline.name') }}">
                </div>

                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Icon') }}</label>
                    <input type="text" name="billing[methods][offline][icon]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300"
                        value="{{ config('billing.methods.offline.icon') }}">
                </div>

                <div class="mb-2">
                    <label for="">Enabled</label>
                    <select name="billing[methods][offline][enable]"
                        class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="" disabled>{{ __('Choose option') }}</option>
                        <option value="{{ true }}"
                            {{ config('billing.methods.offline.enable') == true ? 'selected' : '' }}>
                            {{ __('Yes') }}</option>
                        <option value="{{ false }}"
                            {{ config('billing.methods.offline.enable') == false ? 'selected' : '' }}>
                            {{ __('No') }}</option>
                    </select>
                </div>
            </div>

        </div>
    </div>
@endsection
