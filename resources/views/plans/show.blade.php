<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalles del Plan: {{ $plan->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('plans.edit', $plan) }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    Editar
                </a>
                <a href="{{ route('plans.index') }}" class="px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Información del Plan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Plan</h3>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Nombre</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $plan->name }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500">Estado</p>
                            <p class="mt-1">
                                @if($plan->status)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Activo
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Inactivo
                                    </span>
                                @endif
                            </p>
                        </div>

                        <div class="col-span-2">
                            <p class="text-sm font-medium text-gray-500">Descripción</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $plan->description ?? 'Sin descripción' }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500">Precio Mensual</p>
                            <p class="mt-1 text-sm text-gray-900">
                                @if($plan->monthly_price)
                                    {{ number_format($plan->monthly_price, 2) }} {{ $plan->currency }}/mes
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500">Precio Anual</p>
                            <p class="mt-1 text-sm text-gray-900">
                                @if($plan->yearly_price)
                                    {{ number_format($plan->yearly_price, 2) }} {{ $plan->currency }}/año
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500">Moneda</p>
                            <p class="mt-1 text-sm text-gray-900">
                                @if($plan->currency == 'USD')
                                    Dólares Estadounidenses (USD)
                                @else
                                    Pesos Argentinos (ARS)
                                @endif
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500">Publicación</p>
                            <p class="mt-1">
                                @if($plan->published)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Publicado en panel
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        No publicado
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Límites del Plan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Límites del Plan</h3>
                    
                    <div class="grid grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Máximo Usuarios</p>
                            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $plan->max_users }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Máximo Usuarios</p>
                            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $plan->max_users ?? 'Ilimitado' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Máximo Órdenes de Trabajo</p>
                            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $plan->max_work_orders ?? 'Ilimitado' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Máximo Explotaciones</p>
                            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $plan->max_farms ?? 'Ilimitado' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Máximo Clientes</p>
                            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $plan->max_clients ?? 'Ilimitado' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Clientes pueden crear usuarios</p>
                            <p class="mt-1 text-2xl font-semibold text-gray-900">
                                @if($plan->clients_can_create_users)
                                    <span class="text-green-700">Sí</span>
                                @else
                                    <span class="text-gray-500">No</span>
                                @endif
                            </p>
                        </div>
            </div>

            <!-- Organizaciones con este Plan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Organizaciones Suscritas</h3>
                    
                    @if($plan->organizations->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Organización
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Usuarios
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Explotaciones
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha de Suscripción
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($plan->organizations as $organization)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $organization->name }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $organization->users->count() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $organization->farms->count() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $organization->created_at->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No hay organizaciones suscritas a este plan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
