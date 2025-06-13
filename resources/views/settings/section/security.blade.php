@extends('settings.setting')

@section('form')
    <div class="flex flex-col md:flex-row gap-4 items-start p-4 bg-gray-100 rounded-md shadow">
        <div class="flex-1">
            <h2 class="text-xl font-semibold text-gray-800">{{ __('Security settings') }}</h2>
        </div>

        <div class="w-full md:w-3/4 space-y-4">

            <div class="mb-4 px-2 py-2 border-gray-300   rounded-lg p-4 bg-white shadow-sm">
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
                <small class="block mt-1 text-gray-600">{{ __('This option is used to activate the csp policies') }}</small>
            </div>

            <div class="border-gray-300 rounded-lg p-4 bg-white shadow-sm">
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Captcha driver') }}
                    </label>

                    <select id="captcha" name="services[captcha][driver]"
                        class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="" disabled>{{ __('Choose option') }}</option>
                        <option value="turnstile" {{ config('services.captcha.driver') == 'turnstile' ? 'selected' : '' }}>
                            {{ __('Cloudflare turnstile') }}
                        </option>
                        <option value="hcaptcha" {{ config('services.captcha.driver') == 'hcaptcha' ? 'selected' : '' }}>
                            {{ __('hcaptcha') }}
                        </option>
                    </select>


                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Enable CAPTCHA') }}
                        </label>
                        <select name="services[captcha][enabled]"
                            class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="" disabled>{{ __('Choose option') }}</option>
                            <option value="{{ true }}"
                                {{ config('services.captcha.enabled') == true ? 'selected' : '' }}>
                                {{ __('Yes') }}
                            </option>
                            <option value="{{ false }}"
                                {{ config('services.captcha.enabled') == false ? 'selected' : '' }}>
                                {{ __('No') }}

                            </option>
                        </select>
                    </div>
                </div>

                <div id="turnstile" class="border-gray-300 hidden rounded-lg p-4 bg-white shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2 border-b pb-2">{{ __('Cloudflare Turnstile') }}
                    </h3>
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700">Endpoint</label>
                        <input type="text" name="services[captcha][providers][turnstile][api]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-300"
                            value="{{ config('services.captcha.providers.turnstile.api') }}">
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700">Site key</label>
                        <input type="text" name="services[captcha][providers][turnstile][sitekey]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-300"
                            value="{{ config('services.captcha.providers.turnstile.sitekey') }}">
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700">Secret key</label>
                        <input type="password" name="services[captcha][providers][turnstile][secret]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-300"
                            value="{{ config('services.captcha.providers.turnstile.secret') }}">
                    </div>
                </div>

                <div id="hcaptcha" class="border-gray-300 hidden rounded-lg p-4 bg-white shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2 border-b pb-2">{{ __('Hcaptcha') }}</h3>
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700">Endpoint</label>
                        <input type="text" name="services[captcha][providers][hcaptcha][api]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-300"
                            value="{{ config('services.captcha.providers.hcaptcha.api') }}">
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700">Site key</label>
                        <input type="text" name="services[captcha][providers][hcaptcha][sitekey]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-300"
                            value="{{ config('services.captcha.providers.hcaptcha.sitekey') }}">
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700">Secret key</label>
                        <input type="password" name="services[captcha][providers][hcaptcha][secret]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-300"
                            value="{{ config('services.captcha.providers.hcaptcha.secret') }}">
                    </div>
                </div>
            </div>


            <div class="mb-4 px-2 py-2 border-gray-300   rounded-lg p-4 bg-white shadow-sm">
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
        </div>
    </div>
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        window.document.addEventListener("DOMContentLoaded", function() {

            const captcha = document.getElementById("captcha")
            const turnstile = document.getElementById("turnstile");
            const hcaptcha = document.getElementById("hcaptcha");

            show(captcha.value)

            captcha.addEventListener("change", function(dom) {
                show(dom.target.value)
            })

            function show(value) {
                switch (value) {
                    case 'turnstile':
                        turnstile.classList.remove('hidden')
                        hcaptcha.classList.add('hidden')
                        break;
                    case "hcaptcha":
                        turnstile.classList.add('hidden')
                        hcaptcha.classList.remove('hidden')
                        break;
                    default:
                        break;
                }
            }
        })
    </script>
@endpush
