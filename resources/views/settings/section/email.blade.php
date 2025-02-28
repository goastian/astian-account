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
                        <option value="{{ $driver }}" {{ settingItem('mail.default') == $driver ? 'selected' : '' }}>
                            {{ ucfirst($driver) }}
                        </option>
                    @endforeach
                </select>
            </div>

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
                            value="{{ settingItem('mail.mailers.smtp.' . $key) }}">
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
                            value="{{ settingLoad('services.mailgun.' . $key, null) }}">
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
                            value="{{ settingLoad('services.ses.' . $key, null) }}">
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
                        value="{{ settingLoad('services.passport.token', null) }}">
                </div>
            </div>

            {{-- Email From Address --}}
            <div id="from" class="  border-gray-300 rounded-lg p-4 bg-white shadow-sm">
                <h3 class="text-lg font-semibold text-gray-700 mb-2 border-b pb-2">{{ __('From Address') }}</h3>
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="text" name="mail[from][address]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-300"
                        value="{{ settingItem('mail.from.address') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="mail[from][name]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-300"
                        value="{{ settingItem('mail.from.name') }}">
                </div>
            </div>

            {{-- Configurations --}}
            @foreach ([['verify_account_time', 'Account Verification Time (minutes)'], ['code_2fa_email_expires', '2FA Email Code Expiration Time (Minutes)'], ['auth[passwords][users][expire]', 'Password Reset Expiration Time (Minutes)'], ['auth[passwords][users][throttle]', 'Password Reset Request Throttle (Minutes)']] as [$name, $label])
                <div class="p-4 border border-gray-300 rounded-lg bg-white shadow-sm">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __($label) }}</label>
                    <input type="number" name="{{ $name }}"
                        class="block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:ring-indigo-300"
                        placeholder="{{ __($label) }}" value="{{ settingItem($name, 10) }}">
                    <small class="block mt-1 text-gray-600">{{ __('Specify the duration in minutes.') }}</small>
                </div>
            @endforeach
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
