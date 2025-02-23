<!-- resources/views/settings.blade.php -->
@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Settings')])
@endsection

@section('content')
    <div class="grow-1 mx-4 py-4">
        <div class="flex flex-col md:flex-row gap-2">

            <div class="grow-1 mx-4 ">
                <button id="menu-toggle" class="md:hidden px-4 py-2 text-white bg-indigo-600 rounded-md">
                    <i class="mdi mdi-menu text-2xl"></i>
                </button>

                <ul id="sidebar-menu"
                    class="hidden md:block border w-full   border-gray-200 bg-gray-200 rounded-md divide-y divide-gray-200  md:mb-0">
                    <li class="list-item hover:bg-gray-100 cursor-pointer">
                        <a class="block px-2 py-2" href="{{ route('settings.general') }}">
                            <i class="mdi mdi-home text-2xl"></i> {{ __('General') }}
                        </a>
                    </li>
                    <li class="list-item hover:bg-gray-100 cursor-pointer">
                        <a class="block px-2 py-2" href="{{ route('settings.passport') }}">
                            <i class="mdi mdi-file-key-outline text-2xl"></i> {{ __('Passport') }}
                        </a>
                    </li>
                    <li class="list-item hover:bg-gray-100 cursor-pointer">
                        <a class="block px-2 py-2" href="{{ route('settings.email') }}">
                            <i class="mdi mdi-at text-2xl"></i> {{ __('Email') }}
                        </a>
                    </li>
                    <li class="list-item hover:bg-gray-100 cursor-pointer">
                        <a class="block px-2 py-2" href="{{ route('settings.user') }}">
                            <i class="mdi mdi-account-key text-2xl"></i> {{ __('User') }}
                        </a>
                    </li>
                    <li class="list-item hover:bg-gray-100 cursor-pointer">
                        <a class="block px-2 py-2" href="{{ route('settings.routes') }}">
                            <i class="mdi mdi-router-network text-2xl"></i> {{ __('Routes') }}
                        </a>
                    </li>
                    <li class="list-item hover:bg-gray-100 cursor-pointer">
                        <a class="block px-2 py-2" href="{{ route('settings.redis') }}">
                            <i class="mdi mdi-database-cog text-2xl"></i> {{ __('Redis') }}
                        </a>
                    </li>
                    <li class="list-item hover:bg-gray-100 cursor-pointer">
                        <a class="block px-2 py-2" href="{{ route('settings.queues') }}">
                            <i class="mdi mdi-queue-first-in-last-out text-2xl"></i> {{ __('Queues') }}
                        </a>
                    </li>
                </ul>
            </div>

            <div class="w-full md:w-3/4">
                <form action="{{ route('settings.update') }}" method="post" autocomplete="off">
                    @method('put')
                    @csrf
                    <div>
                        @yield('form')
                    </div>

                    <div class="my-4 flex items-center">
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <i class="mdi mdi-content-save-all"></i> {{ __('Save changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        const menuToggle = document.getElementById('menu-toggle');
        const sidebarMenu = document.getElementById('sidebar-menu');

        menuToggle.addEventListener('click', () => {
            sidebarMenu.classList.toggle('hidden');
        });
    </script>
@endpush
