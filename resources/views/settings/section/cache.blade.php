@extends('settings.setting')

@section('form')
    <div class="flex flex-col md:flex-row gap-4 items-start p-4 bg-gray-100 rounded-md shadow">
        <div class="flex-1">
            <h2 class="text-xl font-semibold text-gray-800">{{ __('Cache settings') }}</h2>
        </div>

        <div class="w-full md:w-3/4 space-y-4">
            <div class="border-gray-300 rounded-lg p-4 bg-white shadow-sm">

                {{-- Driver --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Cache driver') }}
                    </label>
                    <select id="cache-driver" name="cache[default]"
                        class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="" disabled>{{ __('Choose option') }}</option>
                        @foreach (['apc', 'array', 'database', 'file', 'memcached', 'redis', 'dynamodb', 'octane', 'null'] as $driver)
                            <option value="{{ $driver }}" {{ config('cache.default') == $driver ? 'selected' : '' }}>
                                {{ $driver }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Común --}}
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Expires time in days') }}</label>
                    <input type="number" name="cache[expires]" class="block w-full px-2 py-2 rounded-md border-gray-300"
                        value="{{ config('cache.expires') }}">
                </div>

                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Prefix') }}</label>
                    <input type="text" name="cache[prefix]" class="block w-full px-2 py-2 rounded-md border-gray-300"
                        value="{{ config('cache.prefix') }}">
                </div>

                {{-- DATABASE --}}
                <div data-driver="database" class="driver-fields hidden">
                    <h3 class="text-sm font-bold text-gray-600 mt-4 mb-2">Database Store</h3>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Connection</label>
                    <input type="text" name="cache[stores][database][connection]"
                        class="block w-full px-2 py-2 border rounded-md"
                        value="{{ config('cache.stores.database.connection') }}">

                    <label class="block text-sm font-medium text-gray-700 mt-2 mb-1">Table</label>
                    <input type="text" name="cache[stores][database][table]"
                        class="block w-full px-2 py-2 border rounded-md"
                        value="{{ config('cache.stores.database.table') }}">
                </div>

                {{-- REDIS --}}
                <div data-driver="redis" class="driver-fields hidden">
                    <h3 class="text-sm font-bold text-gray-600 mt-4 mb-2">Redis Store</h3>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Connection</label>
                    <input type="text" name="cache[stores][redis][connection]"
                        class="block w-full px-2 py-2 border rounded-md"
                        value="{{ config('cache.stores.redis.connection') }}">

                    <label class="block text-sm font-medium text-gray-700 mt-2 mb-1">Lock Connection</label>
                    <input type="text" name="cache[stores][redis][lock_connection]"
                        class="block w-full px-2 py-2 border rounded-md"
                        value="{{ config('cache.stores.redis.lock_connection') }}">
                </div>

                {{-- MEMCACHED --}}
                <div data-driver="memcached" class="driver-fields hidden">
                    <h3 class="text-sm font-bold text-gray-600 mt-4 mb-2">Memcached Store</h3>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Persistent ID</label>
                    <input type="text" name="cache[stores][memcached][persistent_id]"
                        class="block w-full px-2 py-2 border rounded-md"
                        value="{{ config('cache.stores.memcached.persistent_id') }}">

                    <label class="block text-sm font-medium text-gray-700 mt-2 mb-1">SASL Username</label>
                    <input type="text" name="cache[stores][memcached][sasl][username]"
                        class="block w-full px-2 py-2 border rounded-md"
                        value="{{ config('cache.stores.memcached.sasl.username') }}">

                    <label class="block text-sm font-medium text-gray-700 mt-2 mb-1">SASL Password</label>
                    <input type="text" name="cache[stores][memcached][sasl][password]"
                        class="block w-full px-2 py-2 border rounded-md"
                        value="{{ config('cache.stores.memcached.sasl.password') }}">

                    <label class="block text-sm font-medium text-gray-700 mt-2 mb-1">Host</label>
                    <input type="text" name="cache[stores][memcached][servers][0][host]"
                        class="block w-full px-2 py-2 border rounded-md"
                        value="{{ SettingItem('cache.stores.memcached.servers.0.host') }}">

                    <label class="block text-sm font-medium text-gray-700 mt-2 mb-1">Port</label>
                    <input type="number" name="cache[stores][memcached][servers][0][port]"
                        class="block w-full px-2 py-2 border rounded-md"
                        value="{{ SettingItem('cache.stores.memcached.servers.0.port') }}">

                    <label class="block text-sm font-medium text-gray-700 mt-2 mb-1">Weight</label>
                    <input type="number" name="cache[stores][memcached][servers][0][weight]"
                        class="block w-full px-2 py-2 border rounded-md"
                        value="{{ SettingItem('cache.stores.memcached.servers.0.weight') }}">
                </div>

                {{-- DYNAMODB --}}
                <div data-driver="dynamodb" class="driver-fields hidden">
                    <h3 class="text-sm font-bold text-gray-600 mt-4 mb-2">DynamoDB Store</h3>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Key</label>
                    <input type="text" name="cache[stores][dynamodb][key]"
                        class="block w-full px-2 py-2 border rounded-md" value="{{ config('cache.stores.dynamodb.key') }}">

                    <label class="block text-sm font-medium text-gray-700 mt-2 mb-1">Secret</label>
                    <input type="text" name="cache[stores][dynamodb][secret]"
                        class="block w-full px-2 py-2 border rounded-md"
                        value="{{ config('cache.stores.dynamodb.secret') }}">

                    <label class="block text-sm font-medium text-gray-700 mt-2 mb-1">Region</label>
                    <input type="text" name="cache[stores][dynamodb][region]"
                        class="block w-full px-2 py-2 border rounded-md"
                        value="{{ config('cache.stores.dynamodb.region') }}">

                    <label class="block text-sm font-medium text-gray-700 mt-2 mb-1">Table</label>
                    <input type="text" name="cache[stores][dynamodb][table]"
                        class="block w-full px-2 py-2 border rounded-md"
                        value="{{ config('cache.stores.dynamodb.table') }}">

                    <label class="block text-sm font-medium text-gray-700 mt-2 mb-1">Endpoint</label>
                    <input type="text" name="cache[stores][dynamodb][endpoint]"
                        class="block w-full px-2 py-2 border rounded-md"
                        value="{{ config('cache.stores.dynamodb.endpoint') }}">
                </div>

            </div>
        </div>
    </div>
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener('DOMContentLoaded', function() {
            const driverSelect = document.getElementById('cache-driver');
            const driverFields = document.querySelectorAll('.driver-fields');

            function toggleDriverFields(selectedDriver) {
                driverFields.forEach(div => {
                    div.classList.toggle('hidden', div.dataset.driver !== selectedDriver);
                });
            }

            // Inicializar con el valor actual
            toggleDriverFields(driverSelect.value);

            // Cambiar al seleccionar otra opción
            driverSelect.addEventListener('change', (e) => {
                toggleDriverFields(e.target.value);
            });
        });
    </script>
@endpush
