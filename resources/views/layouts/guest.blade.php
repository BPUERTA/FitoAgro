<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ setting('site_title', config('app.name', 'Laravel')) }}</title>
        @if(setting('favicon'))
            <link rel="icon" type="image/png" href="{{ asset('storage/' . setting('favicon')) }}">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex">
            <!-- Panel izquierdo con imagen de fondo -->
            <div class="hidden lg:flex lg:w-1/2 relative items-center justify-center p-12" style="background-image: url('{{ setting('login_image') ? asset('storage/' . setting('login_image')) : 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=1920&q=85' }}'); background-size: cover; background-position: center;">
                <!-- Overlay oscuro para mejorar legibilidad -->
                <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                <div class="text-white text-center relative z-10">
                    <h1 class="text-5xl font-bold mb-4">{{ setting('site_title', 'FitoAgro Gestión') }}</h1>
                    <p class="text-xl mb-8">{{ setting('site_tagline', 'Sistema de Gestión Agrícola Profesional') }}</p>
                    <div class="space-y-4 text-left max-w-md mx-auto">
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Gestión de explotaciones y cultivos</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Control de productos fitosanitarios</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Gestión de clientes y profesionales</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Reportes y análisis detallados</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel derecho con formulario de login -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
                <div class="w-full max-w-md">
                    <div class="text-center mb-8 lg:hidden">
                        <h1 class="text-3xl font-bold text-green-700">{{ setting('site_title', 'FitoAgro Gestión') }}</h1>
                        <p class="text-gray-600 mt-2">{{ setting('site_tagline', 'Sistema de Gestión Agrícola') }}</p>
                    </div>
                    
                    <div class="bg-white shadow-xl rounded-lg px-8 py-10">
                        {{ $slot }}
                    </div>
                    
                    <div class="text-center mt-6 text-sm text-gray-600">
                        <p>&copy; {{ date('Y') }} {{ setting('site_title', 'FitoAgro Gestión') }}. Todos los derechos reservados.</p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
