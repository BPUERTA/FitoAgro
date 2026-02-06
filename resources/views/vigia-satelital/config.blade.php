<x-app-layout>
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Vigía Satelital – Configuración</h1>
                <p class="text-sm text-gray-500">Define qué alertas y umbrales se usan para tu organización.</p>
            </div>
            <a href="{{ route('vigia-satelital.mapas') }}"
               class="inline-flex items-center rounded-lg bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-300">
                Volver
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('vigia-satelital.config.update') }}" class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <p class="text-sm font-semibold text-gray-800 mb-2">Alertas activas</p>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="ndvi_enabled" value="1" {{ old('ndvi_enabled', $settings->ndvi_enabled) ? 'checked' : '' }} class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-700">Vigor (NDVI)</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="ndmi_enabled" value="1" {{ old('ndmi_enabled', $settings->ndmi_enabled) ? 'checked' : '' }} class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-700">Humedad (NDMI)</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="nbr_enabled" value="1" {{ old('nbr_enabled', $settings->nbr_enabled) ? 'checked' : '' }} class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-700">Quemas (NBR)</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Caída NDVI (umbral)</label>
                    <input type="number" step="0.01" min="0" max="1" name="ndvi_drop_threshold" value="{{ old('ndvi_drop_threshold', $settings->ndvi_drop_threshold) }}" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    <p class="mt-1 text-xs text-gray-500">Ej: 0.10 = 10% de caída.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Caída NDMI (umbral)</label>
                    <input type="number" step="0.01" min="0" max="1" name="ndmi_drop_threshold" value="{{ old('ndmi_drop_threshold', $settings->ndmi_drop_threshold) }}" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Caída NBR (umbral)</label>
                    <input type="number" step="0.01" min="0" max="1" name="nbr_drop_threshold" value="{{ old('nbr_drop_threshold', $settings->nbr_drop_threshold) }}" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Máximo de nubes (%)</label>
                    <input type="number" min="0" max="100" name="cloud_max_percent" value="{{ old('cloud_max_percent', $settings->cloud_max_percent) }}" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Frecuencia</label>
                    <select name="frequency" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        <option value="weekly" {{ old('frequency', $settings->frequency) === 'weekly' ? 'selected' : '' }}>Semanal</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="inline-flex items-center rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">Guardar</button>
            </div>
        </form>
    </div>
</x-app-layout>
