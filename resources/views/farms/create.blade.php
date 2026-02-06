<x-app-layout>
<div class="max-w-2xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Nueva Explotación</h1>
    @if(session('error'))
        <div class="mb-4 p-2 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif
    <form method="POST" action="{{ route('farms.store') }}" class="bg-white p-6 rounded shadow space-y-4">
        @csrf
        <div>
            <label for="client_id" class="block text-sm font-medium text-gray-700">Cliente <span class="text-red-500">*</span></label>
            <select id="client_id" name="client_id" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500" onchange="updateFarmName()">
                <option value="">Seleccione un cliente</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" data-client-name="{{ $client->name }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                        {{ $client->number }} - {{ $client->name }}
                    </option>
                @endforeach
            </select>
            @error('client_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="client_group_id" class="block text-sm font-medium text-gray-700">Grupo de clientes</label>
            <select id="client_group_id" name="client_group_id" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
                <option value="">Sin grupo</option>
                @foreach($clientGroups as $group)
                    <option value="{{ $group->id }}" {{ old('client_group_id') == $group->id ? 'selected' : '' }}>
                        {{ $group->name }}
                    </option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-gray-500">Si asignás un grupo, los porcentajes se usarán para esta explotación.</p>
            @error('client_group_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nombre de la Explotación <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
            <p class="mt-1 text-xs text-gray-500">Se sugiere automáticamente al seleccionar un cliente. Puede editarlo libremente.</p>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="has" class="block text-sm font-medium text-gray-700">Hectáreas totales (Has) <span class="text-red-500">*</span></label>
            <input type="number" step="0.01" min="0" id="has" name="has" value="{{ old('has', 0) }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
            @error('has')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="distancia_poblado" class="block text-sm font-medium text-gray-700">Distancia al Poblado (km)</label>
            <input type="number" step="0.01" min="0" id="distancia_poblado" name="distancia_poblado" value="{{ old('distancia_poblado') }}" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
            @error('distancia_poblado')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="flex items-center">
                <input type="checkbox" name="status" value="1" {{ old('status', true) ? 'checked' : '' }} class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                <span class="ml-2 text-sm text-gray-700">Activo</span>
            </label>
        </div>
        <div class="flex space-x-2">
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Crear</button>
            <a href="{{ route('farms.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Cancelar</a>
        </div>
    </form>
</div>
<script>
function updateFarmName() {
    const clientSelect = document.getElementById('client_id');
    const nameInput = document.getElementById('name');
    const selectedOption = clientSelect.options[clientSelect.selectedIndex];
    
    // Solo actualizar si hay un cliente seleccionado y el campo está vacío o no ha sido editado manualmente
    if (selectedOption.value && selectedOption.dataset.clientName) {
        const clientName = selectedOption.dataset.clientName;
        
        // Si el campo está vacío o tiene el valor de un cliente anterior, actualizarlo
        if (!nameInput.value || nameInput.value.startsWith('Explotación ')) {
            nameInput.value = 'Explotación ' + clientName;
        }
    }
}
</script>
</x-app-layout>

