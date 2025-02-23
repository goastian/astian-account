@extends('settings.index')

@section('form')
    <div class="flex flex-col md:flex-row gap-4 items-start p-4 bg-gray-100 rounded-md shadow">
        <div class="w-full md:w-1/4">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ __('Filesystem Settings') }}
            </h2>
        </div>

        <div class="w-full md:w-3/4 space-y-6">
            {{-- Filesystem Configurations --}}
            <div class="p-4 border border-gray-300 rounded-lg bg-white shadow-md">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">{{ __('Default Filesystem') }}</h3>
                <select name="filesystems[default]" id="filesystem-select"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                    @foreach (['local', 'public', 's3'] as $driver)
                        <option value="{{ $driver }}"
                            {{ settingItem('filesystems.default') == $driver ? 'selected' : '' }}>
                            {{ ucfirst($driver) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Local Disk --}}
            <div id="disk-local" class="disk-settings p-4 border border-green-300 rounded-lg bg-green-50 shadow-md">
                <h3 class="text-lg font-semibold text-green-700 mb-4 border-b pb-2">{{ __('Local Disk') }}</h3>
                @foreach (['driver', 'root', 'throw'] as $key)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            {{ ucfirst($key) }}
                        </label>
                        @if ($key == 'throw')
                            <select name="filesystems[disks][local][{{ $key }}]"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200">
                                <option value="{{ false }}"
                                    {{ settingItem('filesystems.disks.local.throw', false) == false ? 'selected' : '' }}>
                                    {{ __('No') }}
                                </option>
                                <option value="{{ true }}"
                                    {{ settingItem('filesystems.disks.local.throw', false) == true ? 'selected' : '' }}>
                                    {{ __('Yes') }}
                                </option>
                            </select>
                        @else
                            <input type="text" name="filesystems[disks][local][{{ $key }}]"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200"
                                value="{{ settingItem('filesystems.disks.local.' . $key, $key == 'root' ? storage_path('app') : 'local') }}">
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Public Disk --}}
            <div id="disk-public" class="disk-settings p-4 border border-blue-300 rounded-lg bg-blue-50 shadow-md">
                <h3 class="text-lg font-semibold text-blue-700 mb-4 border-b pb-2">{{ __('Public Disk') }}</h3>
                @foreach (['driver', 'root', 'url', 'visibility', 'throw'] as $key)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            {{ ucfirst($key) }}
                        </label>
                        @if ($key == 'throw')
                            <select name="filesystems[disks][public][{{ $key }}]"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                                <option value="{{ false }}"
                                    {{ settingItem('filesystems.disks.public.throw', false) == false ? 'selected' : '' }}>
                                    {{ __('No') }}
                                </option>
                                <option value="{{ true }}"
                                    {{ settingItem('filesystems.disks.public.throw', false) == true ? 'selected' : '' }}>
                                    {{ __('Yes') }}
                                </option>
                            </select>
                        @else
                            <input type="text" name="filesystems[disks][public][{{ $key }}]"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200"
                                value="{{ settingItem('filesystems.disks.public.' . $key, '') }}">
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- S3 Disk --}}
            <div id="disk-s3" class="disk-settings p-4 border border-yellow-300 rounded-lg bg-yellow-50 shadow-md">
                <h3 class="text-lg font-semibold text-yellow-700 mb-4 border-b pb-2">{{ __('S3 Disk') }}</h3>
                @foreach (['driver', 'key', 'secret', 'region', 'bucket', 'url', 'endpoint', 'use_path_style_endpoint', 'throw'] as $key)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            {{ ucfirst(str_replace('_', ' ', $key)) }}
                        </label>
                        @if (in_array($key, ['throw', 'use_path_style_endpoint']))
                            <select name="filesystems[disks][s3][{{ $key }}]"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-yellow-200">
                                <option value="{{ false }}"
                                    {{ settingItem('filesystems.disks.s3.' . $key, false) == false ? 'selected' : '' }}>
                                    {{ __('Yes') }}
                                </option>
                                <option value="{{ true }}"
                                    {{ settingItem('filesystems.disks.s3.' . $key, false) == true ? 'selected' : '' }}>
                                    {{ __('No') }}
                                </option>
                            </select>
                        @else
                            <input type="text" name="filesystems[disks][s3][{{ $key }}]"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-yellow-200"
                                value="{{ settingItem('filesystems.disks.s3.' . $key, '') }}">
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection


@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener("DOMContentLoaded", function() {
            const selectFilesystem = document.getElementById("filesystem-select");
            const disks = ["local", "public", "s3"];

            function toggleDisks() {
                let selected = selectFilesystem.value;
                disks.forEach(disk => {
                    document.getElementById(`disk-${disk}`).style.display = (disk === selected) ? "block" :
                        "none";
                });
            }

            selectFilesystem.addEventListener("change", toggleDisks);
            toggleDisks();
        });
    </script>
@endpush
