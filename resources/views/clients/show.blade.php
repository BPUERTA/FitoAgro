<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-4">
            <a href="{{ route('clients.index') }}"
               class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-sm hover:bg-gray-50">
                ← Volver
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Panel Izquierdo: Detalles del Cliente -->
            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl p-6">
                <div class="mb-6">
                    <h1 class="text-2xl font-semibold text-gray-900">{{ $client->name }}</h1>
                    <p class="text-sm text-gray-500">Detalle del cliente</p>
                </div>

                <div class="space-y-4">
                    <div class="text-sm">
                        <span class="text-gray-500">Número:</span>
                        <span class="font-medium text-gray-900">{{ $client->number }}</span>
                    </div>

                    @if($client->organization_id)
                        <div class="text-sm">
                            <span class="text-gray-500">Organización:</span>
                            <span class="font-medium text-gray-900">{{ $client->organization->name ?? 'N/A' }}</span>
                        </div>
                    @endif

                    <div class="text-sm">
                        <span class="text-gray-500">Domicilio:</span>
                        <span class="font-medium text-gray-900">{{ $client->domicilio ?? 'N/A' }}</span>
                    </div>

                    <div class="text-sm">
                        <span class="text-gray-500">Altura:</span>
                        <span class="font-medium text-gray-900">{{ $client->altura ?? 'N/A' }}</span>
                    </div>

                    <div class="text-sm">
                        <span class="text-gray-500">Localidad:</span>
                        <span class="font-medium text-gray-900">{{ $client->localidad ?? 'N/A' }}</span>
                    </div>

                    <div class="text-sm">
                        <span class="text-gray-500">Provincia:</span>
                        <span class="font-medium text-gray-900">{{ $client->provincia ?? 'N/A' }}</span>
                    </div>

                    <div class="text-sm">
                        <span class="text-gray-500">País:</span>
                        <span class="font-medium text-gray-900">{{ $client->pais ?? 'N/A' }}</span>
                    </div>

                    <div class="text-sm">
                        <span class="text-gray-500">Estado:</span>
                        <span class="font-medium text-gray-900">{{ $client->status }}</span>
                    </div>
                </div>
            </div>

            <!-- Panel Central: Crear Nueva Explotación -->
            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl p-6">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Nueva Explotación</h2>
                    <p class="text-sm text-gray-500">Crear explotación para {{ $client->name }}</p>
                </div>

                <form id="farmForm" action="{{ route('farms.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <input type="hidden" name="client_id" value="{{ $client->id }}">

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre de la Explotación
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}"
                            required
                            placeholder="Ej: Campo Norte"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                        />
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="has" class="block text-sm font-medium text-gray-700 mb-1">
                            Hectáreas (Has)
                        </label>
                        <input 
                            type="number" 
                            id="has" 
                            name="has" 
                            value="{{ old('has') }}"
                            step="0.01"
                            min="0"
                            placeholder="Ej: 150.5"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                        />
                        @error('has')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="distancia_poblado" class="block text-sm font-medium text-gray-700 mb-1">
                            Distancia al Poblado (m)
                        </label>
                        <input 
                            type="number" 
                            id="distancia_poblado" 
                            name="distancia_poblado" 
                            value="{{ old('distancia_poblado') }}"
                            step="1"
                            min="0"
                            placeholder="Ej: 2500"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                        />
                        @error('distancia_poblado')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="status" 
                            name="status"
                            value="1"
                            {{ old('status', true) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-500 focus:ring-green-500"
                        />
                        <label for="status" class="ml-2 text-sm text-gray-700">
                            Activo
                        </label>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <button 
                            type="submit"
                            class="w-full rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                            Crear Explotación
                        </button>
                    </div>
                </form>
            </div>

            <!-- Panel Derecho: Lista de Explotaciones -->
            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl p-6">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Explotaciones</h2>
                    <p class="text-sm text-gray-500">Lista de explotaciones del cliente</p>
                </div>

                @if($farms->count() > 0)
                    <div id="farmsList" class="space-y-3">
                        @foreach($farms as $farm)
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 hover:border-indigo-300 transition-colors">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="font-medium text-gray-900">{{ $farm->name }}</h3>
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $farm->status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $farm->status ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>
                                
                                <div class="space-y-1 text-xs text-gray-600">
                                    @if($farm->has)
                                        <p>📏 {{ $farm->has }} has</p>
                                    @endif
                                    @if($farm->distancia_poblado)
                                        <p>📍 {{ $farm->distancia_poblado }} km</p>
                                    @endif
                                </div>

                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <a href="{{ route('farms.show', $farm->id) }}" 
                                       class="text-xs text-green-600 hover:text-green-800 font-medium">
                                        Ver detalles →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div id="emptyState" class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500 italic">No hay explotaciones registradas</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('#farmForm');
            const farmsList = document.querySelector('#farmsList');
            const emptyState = document.querySelector('#emptyState');
            
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Creando...';
                
                try {
                    const response = await fetch('{{ route("farms.store") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // Limpiar formulario
                        form.reset();
                        document.querySelector('#status').checked = true;
                        
                        // Ocultar estado vacío si existe
                        if (emptyState) {
                            emptyState.remove();
                        }
                        
                        // Crear nueva tarjeta de explotación
                        const farmCard = document.createElement('div');
                        farmCard.className = 'p-4 bg-gray-50 rounded-lg border border-gray-200 hover:border-indigo-300 transition-colors';
                        farmCard.innerHTML = `
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="font-medium text-gray-900">${data.farm.name}</h3>
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium ${data.farm.status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                                    ${data.farm.status ? 'Activo' : 'Inactivo'}
                                </span>
                            </div>
                            <div class="space-y-1 text-xs text-gray-600">
                                ${data.farm.has ? `<p>📏 ${data.farm.has} has</p>` : ''}
                                ${data.farm.distancia_poblado ? `<p>📍 ${data.farm.distancia_poblado} m</p>` : ''}
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <a href="/farms/${data.farm.id}" class="text-xs text-green-600 hover:text-green-800 font-medium">
                                    Ver detalles →
                                </a>
                            </div>
                        `;
                        
                        // Agregar tarjeta al inicio de la lista
                        if (farmsList) {
                            farmsList.insertBefore(farmCard, farmsList.firstChild);
                        }
                        
                        // Mostrar mensaje de éxito
                        const successMsg = document.createElement('div');
                        successMsg.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                        successMsg.textContent = '✓ Explotación creada exitosamente';
                        document.body.appendChild(successMsg);
                        setTimeout(() => successMsg.remove(), 3000);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error al crear la explotación');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Crear Explotación';
                }
            });
        });
    </script>
    @endpush
</x-app-layout>


