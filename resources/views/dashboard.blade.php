<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Panel izquierdo -->
                <div class="lg:col-span-3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                        <div class="flex flex-col md:flex-row md:items-start gap-6">
                            <!-- Lado izquierdo: Mensaje de bienvenida -->
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Bienvenido</h3>
                                <p class="text-gray-600">
                                    Hola, {{ auth()->user()->name }}. Aquí puedes gestionar tus clientes, explotaciones y más.
                                </p>
                            </div>
                            
                            <!-- Lado derecho: Organización, Usuario y Permisos -->
                            <div class="flex-shrink-0 md:w-64 md:ml-auto bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="space-y-3">
                                    <!-- Organización -->
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Organización</p>
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ auth()->user()->organization->name ?? 'Sin organización' }}
                                        </p>
                                    </div>
                                    
                                    <!-- Usuario -->
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Usuario</p>
                                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                    </div>
                                    
                                    <!-- Permisos/Rol -->
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Permisos</p>
                                        <div class="flex flex-wrap gap-2">
                                            @if(auth()->user()->is_admin)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    Super Administrador
                                                </span>
                                            @endif
                                            @if(auth()->user()->is_org_admin)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Admin
                                                </span>
                                            @endif
                                            @if(!auth()->user()->is_admin && !auth()->user()->is_org_admin)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Usuario
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @php($rtEnabled = \Illuminate\Support\Facades\Schema::hasTable('registro_tecnicos'))
                    <!-- Paneles de acceso rápido -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Registro Técnico -->
                        @if($rtEnabled)
                            <a href="{{ route('registro-tecnicos.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 cursor-pointer hover:shadow-md transition-shadow block">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 7h6m-6 4h4" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-semibold text-gray-900">Registro Técnico</h3>
                                        <p class="text-sm text-gray-600">Crea y administra registros técnicos</p>
                                    </div>
                                </div>
                            </a>
                        @endif

                        <!-- Órdenes de trabajo -->
                        <a href="{{ route('work-orders.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 cursor-pointer hover:shadow-md transition-shadow block">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-12 w-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Órdenes de trabajo</h3>
                                    <p class="text-sm text-gray-600">Gestiona las órdenes de trabajo</p>
                                </div>
                            </div>
                        </a>

                        <!-- Vigía Satelital -->
                        <a href="{{ route('vigia-satelital.mapas') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 cursor-pointer hover:shadow-md transition-shadow block">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-12 w-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20l9-5-9-5-9 5 9 5zm0-10l9-5-9-5-9 5 9 5z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Vigía Satelital</h3>
                                    <p class="text-sm text-gray-600">Mapas, alertas y acciones</p>
                                </div>
                            </div>
                        </a>

                        <!-- Reportes -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 cursor-pointer hover:shadow-md transition-shadow">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-12 w-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Reportes</h3>
                                    <p class="text-sm text-gray-600">Visualiza y genera reportes</p>
                                </div>
                            </div>
                        </div>

                        <!-- Recetas de Aplicación -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 cursor-pointer hover:shadow-md transition-shadow">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-12 w-12 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Recetas de Aplicación</h3>
                                    <p class="text-sm text-gray-600">Gestiona recetas de aplicación</p>
                                </div>
                            </div>
                        </div>

                        <!-- Recetas de Venta -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 cursor-pointer hover:shadow-md transition-shadow">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-12 w-12 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Recetas de Venta</h3>
                                    <p class="text-sm text-gray-600">Gestiona recetas de venta</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel derecho con estadísticas -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Estadísticas</h3>
                        </div>

                        <div class="divide-y divide-gray-200">
                            <!-- Usuarios de sistema -->
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Usuarios de sistema</span>
                                    <span class="text-2xl font-bold text-blue-600">{{ $stats['systemUsers'] }}</span>
                                </div>
                            </div>

                            <!-- Usuarios clientes -->
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Usuarios clientes</span>
                                    <span class="text-2xl font-bold text-green-600">{{ $stats['clientUsers'] }}</span>
                                </div>
                            </div>

                            <!-- Clientes -->
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Clientes</span>
                                    <span class="text-2xl font-bold text-green-600">{{ $stats['clients'] }}</span>
                                </div>
                            </div>

                            <!-- Explotaciones -->
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Explotaciones</span>
                                    <span class="text-2xl font-bold text-green-600">{{ $stats['farms'] }}</span>
                                </div>
                            </div>

                            <!-- Organizaciones (solo super admin) -->
                            @if(auth()->user()->is_admin)
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Organizaciones</span>
                                    <span class="text-2xl font-bold text-green-600">{{ $stats['organizations'] }}</span>
                                </div>
                            </div>
                            @endif

                            @if($rtEnabled)
                                <!-- Registro Técnico -->
                                <div class="p-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Registros técnicos</span>
                                        <span class="text-2xl font-bold text-green-600">{{ $stats['registroTecnicos'] }}</span>
                                    </div>
                                </div>
                            @endif

                            <!-- Órdenes de trabajo -->
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Órdenes de trabajo</span>
                                    <span class="text-2xl font-bold text-purple-600">{{ $stats['workOrders'] }}</span>
                                </div>
                            </div>

                            <!-- Recetas de Aplicación -->
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Recetas Aplicación</span>
                                    <span class="text-2xl font-bold text-orange-600">{{ $stats['applicationRecipes'] }}</span>
                                </div>
                            </div>

                            <!-- Recetas de Venta -->
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Recetas Venta</span>
                                    <span class="text-2xl font-bold text-pink-600">{{ $stats['saleRecipes'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
