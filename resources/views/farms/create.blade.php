<x-app-layout>
<div class="max-w-2xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Nueva Explotación</h1>
    @if(session('error'))
        <div class="mb-4 p-2 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif
    <form method="POST" action="{{ route('farms.store') }}" class="bg-white p-6 rounded shadow space-y-4" id="farmForm">
        @csrf
        <div>
            <label for="client_selector_input" class="block text-sm font-medium text-gray-700">Cliente/Grupo</label>
            <input type="text" id="client_selector_input" list="client_selector_list" placeholder="Escribir para buscar" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500" autocomplete="off">
            <datalist id="client_selector_list">
                @foreach($clients as $client)
                    <option value="Cliente: {{ $client->number }} - {{ $client->name }}" data-type="client" data-id="{{ $client->id }}" data-client-name="{{ $client->name }}"></option>
                @endforeach
                @foreach($clientGroups as $group)
                    <option value="Grupo: {{ $group->name }}" data-type="group" data-id="{{ $group->id }}"></option>
                @endforeach
            </datalist>
            <input type="hidden" name="client_id" id="client_id" value="{{ old('client_id') }}">
            <input type="hidden" name="client_group_id" id="client_group_id" value="{{ old('client_group_id') }}">
            <p class="mt-1 text-xs text-gray-500">Elegí un cliente o un grupo. Si elegís un grupo, se asigna automáticamente.</p>
            @error('client_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
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
    const clientInput = document.getElementById('client_selector_input');
    const nameInput = document.getElementById('name');
    if (!clientInput) return;
    const selectedValue = clientInput.value || '';
    const option = document.querySelector(`#client_selector_list option[value="${CSS.escape(selectedValue)}"]`);
    if (option && option.dataset.type === 'client' && option.dataset.clientName) {
        const clientName = option.dataset.clientName;
        if (!nameInput.value || nameInput.value.startsWith('Explotación ')) {
            nameInput.value = 'Explotación ' + clientName;
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const clientInput = document.getElementById('client_selector_input');
    const clientIdInput = document.getElementById('client_id');
    const clientGroupInput = document.getElementById('client_group_id');

    function applySelection() {
        const value = clientInput?.value || '';
        const option = document.querySelector(`#client_selector_list option[value="${CSS.escape(value)}"]`);
        if (option?.dataset.type === 'client') {
            clientIdInput.value = option.dataset.id || '';
            clientGroupInput.value = '';
        } else if (option?.dataset.type === 'group') {
            clientGroupInput.value = option.dataset.id || '';
            clientIdInput.value = '';
        } else {
            clientIdInput.value = '';
            clientGroupInput.value = '';
        }
    }

    clientInput?.addEventListener('change', () => {
        applySelection();
        updateFarmName();
    });
    clientInput?.addEventListener('blur', () => {
        applySelection();
        updateFarmName();
    });

    if (clientIdInput.value) {
        const option = document.querySelector(`#client_selector_list option[data-type="client"][data-id="${clientIdInput.value}"]`);
        if (option) {
            clientInput.value = option.value;
        }
    } else if (clientGroupInput.value) {
        const option = document.querySelector(`#client_selector_list option[data-type="group"][data-id="${clientGroupInput.value}"]`);
        if (option) {
            clientInput.value = option.value;
        }
    }

    applySelection();
    updateFarmName();
});
</script>
</x-app-layout>

