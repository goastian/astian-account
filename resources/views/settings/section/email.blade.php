@extends('settings.setting')

@section('form')
    <div class="flex flex-col md:flex-row gap-4 items-start p-6 bg-gray-50 rounded-lg shadow-sm border border-gray-200">

        <div class="w-full md:w-1/4">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ __('Email settings') }}
            </h2>
        </div>

        <div class="w-full md:w-3/4 space-y-4">

            {{-- Default Mailer --}}
            <div class="border border-gray-300 p-4 rounded-lg bg-white shadow-sm">
                <h3 class="text-lg font-semibold text-gray-700 mb-2 border-b pb-2">{{ __('Default Mailer') }}</h3>
                <select name="mail[default]" id="mail_selector"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-300">
                    @foreach (['smtp', 'ses', 'mailgun', 'postmark', 'sendmail', 'log', 'array', 'failover'] as $driver)
                        <option value="{{ $driver }}" {{ config('mail.default') == $driver ? 'selected' : '' }}>
                            {{ ucfirst($driver) }}
                        </option>
                    @endforeach
                </select>

                <div class="pb-3"></div>

                {{-- SMTP Settings --}}
                <div id="smtp" class="mail-config p-4 border border-blue-300 rounded-lg bg-blue-50 shadow-sm hidden">
                    <h3 class="text-lg font-semibold text-blue-700 mb-2 border-b pb-2">{{ __('SMTP Mailer') }}</h3>
                    @foreach (['host', 'port', 'encryption', 'username', 'password', 'timeout', 'local_domain'] as $key)
                        <div class="mb-2">
                            <label
                                class="block text-sm font-medium text-gray-700">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>
                            <input type="{{ $key === 'password' ? 'password' : 'text' }}"
                                name="mail[mailers][smtp][{{ $key }}]"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300"
                                value="{{ config('mail.mailers.smtp.' . $key) }}">
                        </div>
                    @endforeach
                </div>


                {{-- Mailgun Settings --}}
                <div id="mailgun" class="mail-config border border-gray-300 rounded-lg p-4 bg-white shadow-sm hidden">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2 border-b pb-2">{{ __('Mailgun Settings') }}</h3>
                    @foreach (['domain', 'secret', 'endpoint', 'scheme'] as $key)
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">{{ ucfirst($key) }}</label>
                            <input type="text" name="services[mailgun][{{ $key }}]"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-300"
                                value="{{ config('services.mailgun.' . $key, null) }}">
                        </div>
                    @endforeach
                </div>

                {{-- SES Settings --}}
                <div id="ses" class="mail-config border border-gray-300 rounded-lg p-4 bg-white shadow-sm hidden">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2 border-b pb-2">{{ __('SES Settings') }}</h3>
                    @foreach (['key', 'secret', 'region'] as $key)
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">{{ ucfirst($key) }}</label>
                            <input type="text" name="services[ses][{{ $key }}]"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-300"
                                value="{{ config('services.ses.' . $key, null) }}">
                        </div>
                    @endforeach
                </div>

                {{-- Passport Settings --}}
                <div id="passport" class="mail-config border border-gray-300 rounded-lg p-4 bg-white shadow-sm hidden">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2 border-b pb-2">{{ __('Passport Settings') }}</h3>
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700">Token</label>
                        <input type="text" name="services[passport][token]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-300"
                            value="{{ config('services.passport.token', null) }}">
                    </div>
                </div>

                {{-- Email From Address --}}
                <div id="from" class="  border-gray-300 rounded-lg p-4 bg-white shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2 border-b pb-2">{{ __('From Address') }}</h3>
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="text" name="mail[from][address]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-300"
                            value="{{ config('mail.from.address') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="mail[from][name]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-300"
                            value="{{ config('mail.from.name') }}">
                    </div>
                </div>
            </div>

            {{-- Configurations --}}

            <div class="p-4 border border-gray-300 rounded-lg bg-white shadow-sm">
                <label
                    class="block text-sm font-medium text-gray-700 mb-2">{{ __(key: 'Account Verification Time (minutes)') }}</label>
                <input type="number" name="system[verify_account_time]"
                    class="block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:ring-indigo-300"
                    value="{{ config('system.verify_account_time', 5) }}">
                <small class="block mt-1 text-gray-600">{{ __('Specify the duration in minutes.') }}</small>
            </div>


            <div class="p-4 border border-gray-300 rounded-lg bg-white shadow-sm">
                <label
                    class="block text-sm font-medium text-gray-700 mb-2">{{ __('2FA Email Code Expiration Time (Minutes)') }}</label>
                <input type="number" name="system[code_2fa_email_expires]"
                    class="block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:ring-indigo-300"
                    value="{{ config('system.code_2fa_email_expires', 5) }}">
                <small class="block mt-1 text-gray-600">{{ __('Specify the duration in minutes.') }}</small>
            </div>


            <div class="p-4 border border-gray-300 rounded-lg bg-white shadow-sm">
                <label
                    class="block text-sm font-medium text-gray-700 mb-2">{{ __('Password Reset Expiration Time (Minutes)') }}</label>
                <input type="number" name="auth[passwords][users][expire]"
                    class="block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:ring-indigo-300"
                    value="{{ config('auth.passwords.users.expire', 10) }}">
                <small class="block mt-1 text-gray-600">{{ __('Specify the duration in minutes.') }}</small>
            </div>

            <div class="p-4 border border-gray-300 rounded-lg bg-white shadow-sm">
                <label
                    class="block text-sm font-medium text-gray-700 mb-2">{{ __('Password Reset Request Throttle (Minutes)') }}</label>
                <input type="number" name="auth[passwords][users][throttle]"
                    class="block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:ring-indigo-300"
                    value="{{ config('auth.passwords.users.throttle', 10) }}">
                <small class="block mt-1 text-gray-600">{{ __('Specify the duration in minutes.') }}</small>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener('DOMContentLoaded', function() {
            const selector = document.getElementById('mail_selector');
            const configs = document.querySelectorAll('.mail-config');

            function updateVisibility() {
                configs.forEach(config => config.classList.add('hidden'));
                const selectedMailer = document.getElementById(selector.value);
                if (selectedMailer) selectedMailer.classList.remove('hidden');
            }

            selector.addEventListener('change', updateVisibility);
            updateVisibility();
        });
    </script>
@endpush
