<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Planes</h1>
                <p class="text-sm text-gray-500">Gestión de planes de suscripción</p>
            </div>

            <a href="{{ route('plans.create') }}"
               class="inline-flex items-center rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Plan
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4">
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Mensual</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Anual</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Moneda</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Límites</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Publicado</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($plans as $plan)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                    {{ $plan->name }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    @if($plan->monthly_price)
                                        {{ number_format($plan->monthly_price, 2) }}
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    @if($plan->yearly_price)
                                        {{ number_format($plan->yearly_price, 2) }}
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $plan->currency }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    <div class="space-y-1">
                                        @if($plan->max_users)
                                            <div>Usuarios: {{ $plan->max_users }}</div>
                                        @endif
                                        @if($plan->max_work_orders)
                                            <div>Órdenes: {{ $plan->max_work_orders }}</div>
                                        @endif
                                        @if($plan->max_farms)
                                            <div>Explotaciones: {{ $plan->max_farms }}</div>
                                        @endif
                                        @if($plan->max_clients)
                                            <div>Clientes: {{ $plan->max_clients }}</div>
                                        @endif
                                        <div>
                                            <span class="text-xs {{ $plan->clients_can_create_users ? 'text-green-700' : 'text-gray-500' }}">
                                                {{ $plan->clients_can_create_users ? 'Clientes pueden crear usuarios' : 'Clientes no pueden crear usuarios' }}
                                            </span>
                                        </div>
                                        @if(!$plan->max_users && !$plan->max_work_orders && !$plan->max_farms && !$plan->max_clients)
                                            <span class="text-gray-400">Sin límites</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    @if($plan->status)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">
                                            Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    @if($plan->published)
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">
                                            Publicado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800">
                                            No publicado
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('plans.show', $plan) }}" 
                                           class="text-blue-600 hover:text-blue-800"
                                           title="Ver">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('plans.edit', $plan) }}" 
                                           class="text-indigo-600 hover:text-indigo-800"
                                           title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('plans.destroy', $plan) }}" method="POST" class="inline"
                                              onsubmit="return confirm('¿Estás seguro de eliminar este plan?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800" title="Eliminar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    No hay planes registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
