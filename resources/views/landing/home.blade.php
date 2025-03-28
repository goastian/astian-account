@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => settingItem('app.name', 'Oauth2 Server')])
@endsection

@section('header')
    @include('layouts.parts.header')
@endsection

@section('content')
    <main class="flex-grow bg-white">

        <section class="w-full bg-white py-40 px-6">
            <div class="container mx-auto max-w-3xl text-center space-y-8">
                <div class="space-y-4">
                    <h1 class="text-4xl md:text-5xl font-medium text-gray-800 leading-tight">
                        Welcome to
                        <span class="text-teal-500 font-semibold block">
                        Astian Account
                        </span>
                    </h1>
                    <p class="text-gray-600 max-w-xl mx-auto">
                        Access your account or sign up to start managing your data easily and securely on our platform.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row justify-center gap-4 pt-4">
                    <a href="{{ route('login') }}" class="px-6 py-2 bg-teal-500 text-white font-semibold rounded-lg shadow-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-opacity-50 transition-colors">
                        <i class="mdi mdi-login"></i> {{ __('Login') }}
                    </a>
                    @if (settingItem('enable_register_member', true))
                        <a href="{{ route('register') }}" class="px-6 py-2 border-2 border-teal-600 text-teal-600 font-semibold rounded-lg shadow-md hover:bg-teal-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-opacity-50 transition-colors">
                            <i class="mdi mdi-account-edit-outline"></i> {{ __('Register') }}
                        </a>
                    @endif
                </div>
            </div>
        </section>

        <section class="py-20 px-6">
          <div class="container mx-auto text-center mb-16">
            <div class="inline-block px-4 py-1 bg-green-200 rounded-full text-sm font-medium text-green-700 mb-4">
                Main Features
            </div>

            <h2 class="text-3xl md:text-4xl font-bold mb-6 text-gray-800">
                Why Choose Us?
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Our platform offers advanced tools to manage your accounts securely and efficiently, all in one place.
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">

            <!-- 1 -->
            <div class="p-6 border border-gray-200 rounded-lg shadow-md hover:shadow-lg hover:transform hover:scale-105 transition-all duration-300">
                <!-- Icono dentro de un contenedor con fondo y borde redondeado -->
                <div class="w-12 h-12 bg-red-100 text-red-300 rounded-lg flex items-center justify-center mb-4 group-hover:bg-secondary/20 transition-colors">
                    <i class="mdi mdi-shield-lock text-3xl"></i>
                </div>
                <!-- Título -->
                <h3 class="text-xl font-semibold mb-2 text-gray-800">Increased Security</h3>
                <!-- Descripción -->
                <p class="text-gray-600 mb-4">
                    Protect sensitive data with encryption.
                </p>
            </div>

            <!-- 2 -->
            <div class="p-6 border border-gray-200 rounded-lg shadow-md hover:shadow-lg hover:transform hover:scale-105 transition-all duration-300">
                <!-- Icono dentro de un contenedor con fondo y borde redondeado -->
                <div class="w-12 h-12 rounded-lg bg-sky-100 text-sky-300 flex items-center justify-center mb-4 group-hover:bg-secondary/20 transition-colors">
                    <i class="mdi mdi-gamepad-circle text-3xl"></i>
                </div>
                <!-- Título -->
                <h3 class="text-xl font-semibold mb-2 text-gray-800">Centralized Control</h3>
                <!-- Descripción -->
                <p class="text-gray-600 mb-4">
                    Manage access across multiple applications.
                </p>
            </div>

            <!-- 3 -->
            <div class="p-6 border border-gray-200 rounded-lg shadow-md hover:shadow-lg hover:transform hover:scale-105 transition-all duration-300">
                <!-- Icono dentro de un contenedor con fondo y borde redondeado -->
                <div class="w-12 h-12 rounded-lg bg-purple-100 text-purple-300 flex items-center justify-center mb-4 group-hover:bg-secondary/20 transition-colors">
                    <i class="mdi mdi-cog text-3xl"></i>
                </div>
                <!-- Título -->
                <h3 class="text-xl font-semibold mb-2 text-gray-800">Seamless Integration</h3>
                <!-- Descripción -->
                <p class="text-gray-600 mb-4">
                    Easily integrate with existing systems.
                </p>
            </div>

          </div>
        </section>

    </main>
@endsection

@section('footer')
    @include('layouts.parts.footer')
@endsection
