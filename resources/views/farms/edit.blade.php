<x-app-layout>
@php
    $lotsTotal = $farm->lots->sum('has');
    $lotsRemaining = max(0, (float) $farm->has - (float) $lotsTotal);
@endphp
<div class="max-w-none mx-auto w-full py-8 px-4">
    <h1 class="text-2xl font-bold mb-6">Editar Explotación</h1>
    @if(session('error'))
        <div class="mb-4 p-2 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif
    
    <div class="farm-edit-layout">
        <div class="farm-edit-left space-y-6 w-full">
            <!-- Formulario -->
            <div class="bg-white p-6 rounded shadow" data-remaining-has="{{ number_format($lotsRemaining, 2, '.', '') }}">
                <form method="POST" action="{{ route('farms.update', $farm) }}" id="farmForm" class="space-y-4">
                @csrf
                @method('PUT')
                
                <input type="hidden" name="polygon_coordinates" id="polygon_coordinates" value="{{ old('polygon_coordinates', $farm->polygon_coordinates) }}">
                <input type="hidden" name="lat" id="lat" value="{{ old('lat', $farm->lat) }}">
                <input type="hidden" name="lng" id="lng" value="{{ old('lng', $farm->lng) }}">
                
                <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                    <select id="client_id" name="client_id" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500" onchange="updateFarmName()">
                        <option value="">Seleccione un cliente</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" 
                                    data-client-name="{{ $client->name }}" 
                                    {{ old('client_id', $farm->client_id) == $client->id ? 'selected' : '' }}>
                                {{ $client->number }} - {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Podés elegir un cliente o un grupo. Si elegís un grupo, se asigna automáticamente.</p>
                    @error('client_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="client_group_id" class="block text-sm font-medium text-gray-700">Grupo de clientes</label>
                    <select id="client_group_id" name="client_group_id" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
                        <option value="">Sin grupo</option>
                        @foreach($clientGroups as $group)
                            <option value="{{ $group->id }}" {{ old('client_group_id', $farm->client_group_id) == $group->id ? 'selected' : '' }}>
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
                    <input type="text" id="name" name="name" value="{{ old('name', $farm->name) }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
                    <p class="mt-1 text-xs text-gray-500">Se sugiere automáticamente al seleccionar un cliente. Puede editarlo libremente.</p>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="has" class="block text-sm font-medium text-gray-700">Hectáreas totales (Has) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0" id="has" name="has" value="{{ old('has', $farm->has) }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500" data-has-input>
                    @error('has')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="-mt-2">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" id="auto_area" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                        Área Polígono
                    </label>
                    <p class="text-xs text-gray-500 mt-1">Si está activo, las has se actualizan con el área del polígono de la explotación.</p>
                </div>
                
                <div>
                    <label for="distancia_poblado" class="block text-sm font-medium text-gray-700">Distancia al Poblado (m)</label>
                    <input type="number" step="1" min="0" id="distancia_poblado" name="distancia_poblado" value="{{ old('distancia_poblado', $farm->distancia_poblado) }}" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
                    @error('distancia_poblado')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="status" value="1" {{ old('status', $farm->status) ? 'checked' : '' }} class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-700">Activo</span>
                    </label>
                </div>

                
                <div class="flex justify-end gap-2">
                    <a href="{{ route('farms.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Volver</a>
                    <button type="button" id="farm-polygon-btn" class="px-4 py-2 rounded bg-red-50 text-red-700 hover:bg-red-100">Pol&iacute;gono Explotaci&oacute;n</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Actualizar</button>
                    <a href="{{ route('clients.show', $farm->client_id) }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Cancelar</a>
                </div>
                </form>
            </div>

            <!-- Lotes -->
            <div class="bg-white p-6 rounded shadow">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Lotes</h2>
                    <button type="button" id="add-lot-btn" class="px-3 py-2 rounded bg-green-600 text-white text-sm hover:bg-green-700">Agregar lote</button>
                </div>
                <div class="mb-4 text-sm text-gray-600">
                    Total explotación: <span class="font-semibold text-gray-800">{{ number_format($farm->has, 2) }}</span> Has ·
                    Sumatoria lotes: <span class="font-semibold text-gray-800">{{ number_format($lotsTotal, 2) }}</span> Has ·
                    Disponible: <span class="font-semibold text-gray-800">{{ number_format($lotsRemaining, 2) }}</span> Has
                </div>

                <div id="new-lot-inline" class="hidden mb-6 border border-gray-200 rounded-lg p-3">
                    <form method="POST" action="{{ route('farms.lots.store', $farm) }}" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                        @csrf
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-600">Nombre</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500" id="new-lot-name">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Has</label>
                            <input type="number" step="any" min="0.01" inputmode="decimal" name="has" value="{{ old('has') }}" required class="mt-1 w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500" data-lot-has id="new-lot-has">
                        </div>
                        <div class="flex flex-wrap items-center gap-2 text-sm text-gray-700">
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="is_agricola" value="1" {{ old('is_agricola') ? 'checked' : '' }} class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                                Agrícola
                            </label>
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="is_ganadera" value="1" {{ old('is_ganadera') ? 'checked' : '' }} class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                                Ganadero
                            </label>
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="is_otro" value="1" {{ old('is_otro') ? 'checked' : '' }} class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                                Otro
                            </label>
                        </div>
                        <div class="md:col-span-2">
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="vigia_alerts_enabled" value="1" {{ old('vigia_alerts_enabled') ? 'checked' : '' }} class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                                Alerta satelital
                            </label>
                        </div>
                        <div class="md:col-span-4 flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700">Guardar</button>
                        </div>
                    </form>
                </div>

                @if($farm->lots->isEmpty())
                    <p class="text-sm text-gray-500 mb-4">Aún no hay lotes registrados.</p>
                @else
                    <div class="space-y-3 mb-6">
                        @foreach($farm->lots as $lot)
                            <div class="relative border border-gray-200 rounded-lg p-3" data-lot-row="{{ $lot->id }}">
                                <form method="POST" action="{{ route('farms.lots.update', [$farm, $lot]) }}" class="lot-update-form">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="polygon_coordinates" value="{{ old('polygon_coordinates', $lot->polygon_coordinates) }}" data-lot-polygon="{{ $lot->id }}">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-medium text-gray-600">Nombre</label>
                                            <input type="text" name="name" value="{{ old('name', $lot->name) }}" required class="mt-1 w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600">Has</label>
                                        <input type="number" step="any" min="0.01" inputmode="decimal" name="has" value="{{ old('has', $lot->has) }}" required class="mt-1 w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500" data-lot-has>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-2 text-sm text-gray-700">
                                            <label class="inline-flex items-center gap-2">
                                                <input type="checkbox" name="is_agricola" value="1" {{ old('is_agricola', $lot->is_agricola) ? 'checked' : '' }} class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                                                Agrícola
                                            </label>
                                            <label class="inline-flex items-center gap-2">
                                                <input type="checkbox" name="is_ganadera" value="1" {{ old('is_ganadera', $lot->is_ganadera) ? 'checked' : '' }} class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                                                Ganadero
                                            </label>
                                            <label class="inline-flex items-center gap-2">
                                                <input type="checkbox" name="is_otro" value="1" {{ old('is_otro', $lot->is_otro ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                                                Otro
                                            </label>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                                <input type="checkbox" name="vigia_alerts_enabled" value="1" {{ old('vigia_alerts_enabled', $lot->vigia_alerts_enabled) ? 'checked' : '' }} class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                                                Alerta satelital
                                            </label>
                                        </div>
                                        <div class="md:col-span-2 md:col-start-3 flex items-start justify-end gap-2 pt-1">
                                            <button type="button" class="px-3 py-2 bg-blue-50 text-blue-700 text-sm rounded hover:bg-blue-100" data-lot-target="{{ $lot->id }}">Polígono</button>
                                            <button type="submit" class="px-3 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700">Guardar</button>
                                            <button type="submit" form="delete-lot-{{ $lot->id }}" class="px-3 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700">Eliminar</button>
                                        </div>
                                    </div>
                                </form>
                                <form id="delete-lot-{{ $lot->id }}" method="POST" action="{{ route('farms.lots.destroy', [$farm, $lot]) }}" onsubmit="return confirm('¿Eliminar lote?');">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>

        <!-- Mapa para dibujar polígono -->
<div class="farm-edit-right bg-white p-6 rounded shadow w-full relative z-0">
            <div class="mb-3 flex items-center justify-between gap-3">
                <h2 class="text-lg font-semibold text-gray-800">Marcar Polígono en el Mapa</h2>
                <button type="button" id="map-instructions-btn" class="px-3 py-1.5 rounded bg-gray-100 text-gray-700 hover:bg-gray-200">Instrucciones</button>
            </div>
            <div class="mb-3 flex flex-wrap items-center gap-2 text-sm">
                <span class="text-gray-600">Editando:</span>
                <span id="map-target-label" class="font-semibold text-gray-800">Explotación</span>
            </div>
            <div id="map" class="w-full rounded border border-gray-300" style="height:520px;"></div>
            <button type="button" onclick="clearPolygon()" class="mt-3 px-4 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                Borrar Polígono
            </button>
            
            @if($farm->polygon_coordinates && $farm->lat && $farm->lng)
                <div class="mt-4 p-3 bg-gray-50 rounded border border-gray-200">
                    <p class="text-sm text-gray-700 mb-2 font-medium">ð Compartir ubicación:</p>
                    <a href="https://www.google.com/maps?q={{ $farm->lat }},{{ $farm->lng }}" 
                       target="_blank" 
                       class="inline-flex items-center text-sm text-green-600 hover:text-green-800 hover:underline">
                        Abrir en Google Maps â
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<div id="area-warning-modal" class="hidden fixed inset-0 z-[2147483647] flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold text-gray-900">Diferencia de superficie</h3>
            <button type="button" id="area-warning-close-x" class="h-8 w-8 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200" aria-label="Cerrar">â</button>
        </div>
        <p class="text-sm text-gray-700 mb-5" id="area-warning-text"></p>
        <div class="flex justify-end gap-2">
            <button type="button" id="area-warning-close" class="px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-black">Cerrar</button>
        </div>
    </div>
</div>

<div id="map-instructions-modal" class="hidden fixed inset-0 z-[2147483647] flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold text-gray-900">Instrucciones</h3>
            <button type="button" id="map-instructions-close-x" class="h-8 w-8 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200" aria-label="Cerrar">â</button>
        </div>
        <ul class="space-y-2 text-sm text-gray-700">
            <li>Haz clic en el botón de dibujar polígono (ícono de polígono en la parte superior del mapa).</li>
            <li>Haz clic en el mapa para marcar cada punto del polígono.</li>
            <li>Haz clic en el primer punto nuevamente para cerrar el polígono.</li>
            <li>Puedes borrar el polígono y dibujar uno nuevo.</li>
        </ul>
        <div class="flex justify-end gap-2 mt-5">
            <button type="button" id="map-instructions-close" class="px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-black">Cerrar</button>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
<script src="https://unpkg.com/leaflet-geometryutil@0.10.2/src/leaflet.geometryutil.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>

<style>
    .farm-edit-layout {
        display: block;
    }
    @media (min-width: 768px) {
        .farm-edit-layout {
            display: grid;
            grid-template-columns: 3.3% 30% 3.3% 60% 3.3%;
            align-items: start;
        }
        .farm-edit-left {
            grid-column: 2;
        }
        .farm-edit-right {
            grid-column: 4;
        }
    }
    #map {
        position: relative;
        z-index: 0;
    }
    .leaflet-pane,
    .leaflet-control {
        z-index: 0;
    }
    #map .leaflet-top.leaflet-left {
        z-index: 1;
    }
    #map .leaflet-control-geocoder {
        margin-top: 12px;
        margin-left: 12px;
        box-shadow: 0 10px 18px rgba(15, 23, 42, 0.15);
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        background: #fff;
        overflow: hidden;
    }
    #map .leaflet-control-geocoder input {
        width: 240px;
    }
    #map .leaflet-control-geocoder-alternatives {
        max-height: 260px;
        overflow: auto;
    }
    #map .leaflet-control-geocoder {
        box-shadow: none;
    }
</style>

<script>
    let map;
    let drawnItems;
    let currentPolygon = null;
    let activeTarget = { type: 'farm', id: null };
    let applyEditLock = () => {};

    const layerTargets = new Map();
    const farmTolerancePct = 2;
    const snapDistanceMeters = 5;
    function normalizeDecimalInput(value) {
        return value.replace(',', '.');
    }

    function setActiveTarget(type, id = null) {
        activeTarget = { type, id };
        const label = document.getElementById('map-target-label');
        if (label) {
            label.textContent = type === 'farm' ? 'Explotación' : `Lote #${id}`;
        }
        applyEditLock();
        document.querySelectorAll('[data-lot-row]').forEach((row) => {
            row.classList.toggle('ring-2', row.dataset.lotRow === String(id));
            row.classList.toggle('ring-blue-200', row.dataset.lotRow === String(id));
        });
    }

    function savePolygonForTarget(latlngs, target) {
        const coords = latlngs.map((latlng) => ({ lat: latlng.lat, lng: latlng.lng }));
        const areaHa = calculateHectares(latlngs);
        if (target.type === 'farm') {
            document.getElementById('polygon_coordinates').value = JSON.stringify(coords);
            let centerLat = 0;
            let centerLng = 0;
            coords.forEach((coord) => {
                centerLat += coord.lat;
                centerLng += coord.lng;
            });
            centerLat /= coords.length;
            centerLng /= coords.length;
            document.getElementById('lat').value = centerLat;
            document.getElementById('lng').value = centerLng;
            const hasInput = document.querySelector('[data-has-input]');
            const autoArea = document.getElementById('auto_area');
            const declaredBefore = parseFloat(hasInput?.value || '0');
            if (autoArea) {
                autoArea.checked = true;
            }
            const declared = declaredBefore;
            if (declared > 0) {
                const diffPct = Math.abs(areaHa - declared) / declared * 100;
                if (diffPct > farmTolerancePct) {
                    showAreaWarning(declared, areaHa, diffPct);
                }
            }
            if (hasInput && areaHa > 0) {
                hasInput.value = areaHa.toFixed(2);
            }
            return;
        }
        const input = document.querySelector(`[data-lot-polygon="${target.id}"]`);
        if (input) {
            input.value = JSON.stringify(coords);
        }
        const row = document.querySelector(`[data-lot-row="${target.id}"]`);
        const hasInput = row?.querySelector('input[name="has"]');
        if (hasInput && areaHa > 0) {
            hasInput.value = areaHa.toFixed(2);
        }
        updateLotColorFromRow(row, target.id);
    }

    function getFarmLayer() {
        let farmLayer = null;
        drawnItems?.eachLayer((layer) => {
            if (layer._target && layer._target.type === 'farm') {
                farmLayer = layer;
            }
        });
        return farmLayer;
    }

    function getLotLayers(excludeId = null) {
        const layers = [];
        drawnItems?.eachLayer((layer) => {
            if (layer._target && layer._target.type === 'lot' && String(layer._target.id) !== String(excludeId)) {
                layers.push(layer);
            }
        });
        return layers;
    }

    function getLotColor(row) {
        const agricola = row?.querySelector('input[name="is_agricola"]')?.checked;
        const ganadero = row?.querySelector('input[name="is_ganadera"]')?.checked;
        const otro = row?.querySelector('input[name="is_otro"]')?.checked;

        if (agricola && ganadero) return '#F97316';
        if (agricola) return '#16A34A';
        if (ganadero) return '#1D4ED8';
        if (otro) return '#F59E0B';
        return '#1D4ED8';
    }

    function updateLotColorFromRow(row, lotId) {
        const color = getLotColor(row);
        drawnItems?.eachLayer((layer) => {
            if (layer._target && layer._target.type === 'lot' && String(layer._target.id) === String(lotId)) {
                layer.setStyle({ color, fillColor: color, fillOpacity: 0.25, weight: 2 });
            }
        });
    }

    function latlngsToTurf(latlngs) {
        if (!latlngs || !latlngs.length) return null;
        const ring = latlngs.map((ll) => [ll.lng, ll.lat]);
        const first = ring[0];
        const last = ring[ring.length - 1];
        if (first[0] !== last[0] || first[1] !== last[1]) {
            ring.push([first[0], first[1]]);
        }
        return turf.polygon([ring]);
    }

    function validateLotPolygon(layer) {
        const farmLayer = getFarmLayer();
        if (!farmLayer) {
            showAreaWarningMessage('Primero debés dibujar el polígono de la explotación.');
            return false;
        }
        const lotLatLngs = layer.getLatLngs()[0] || [];
        const farmLatLngs = farmLayer.getLatLngs()[0] || [];
        const lotPoly = latlngsToTurf(lotLatLngs);
        const farmPoly = latlngsToTurf(farmLatLngs);
        if (!lotPoly || !farmPoly) {
            return false;
        }
        if (!turf.booleanWithin(lotPoly, farmPoly)) {
            showAreaWarningMessage('El polígono del lote no puede salir del polígono de la explotación.');
            return false;
        }
        const otherLots = getLotLayers(layer._target?.id);
        for (const other of otherLots) {
            const otherPoly = latlngsToTurf(other.getLatLngs()[0] || []);
            if (otherPoly && !turf.booleanDisjoint(lotPoly, otherPoly)) {
                showAreaWarningMessage('El polígono del lote no puede superponerse con otro lote.');
                return false;
            }
        }
        return true;
    }

    function collectSnapPoints() {
        const points = [];
        drawnItems?.eachLayer((layer) => {
            if (!layer.getLatLngs) return;
            const latlngs = layer.getLatLngs()[0] || [];
            latlngs.forEach((ll) => points.push(ll));
        });
        return points;
    }

    function snapLatLngs(latlngs) {
        const points = collectSnapPoints();
        if (!points.length) return latlngs;
        return latlngs.map((ll) => {
            let nearest = null;
            let minDist = Infinity;
            for (const p of points) {
                const d = map.distance(ll, p);
                if (d < minDist) {
                    minDist = d;
                    nearest = p;
                }
            }
            if (nearest && minDist <= snapDistanceMeters) {
                return L.latLng(nearest.lat, nearest.lng);
            }
            return ll;
        });
    }

    function calculateHectares(latlngs) {
        if (!latlngs || !latlngs.length) return 0;
        if (window.L && L.GeometryUtil && typeof L.GeometryUtil.geodesicArea === 'function') {
            const areaM2 = L.GeometryUtil.geodesicArea(latlngs);
            return areaM2 / 10000;
        }
        return 0;
    }

    function showAreaWarning(declared, calculated, diffPct) {
        const modal = document.getElementById('area-warning-modal');
        const text = document.getElementById('area-warning-text');
        if (!modal || !text) return;
        text.textContent = `El polígono mide ${calculated.toFixed(2)} ha, y el total declarado es ${declared.toFixed(2)} ha (diferencia ${diffPct.toFixed(2)}%).`;
        modal.classList.remove('hidden');
    }

    function showAreaWarningMessage(message) {
        const modal = document.getElementById('area-warning-modal');
        const text = document.getElementById('area-warning-text');
        if (!modal || !text) return;
        text.textContent = message;
        modal.classList.remove('hidden');
    }

    function initMap() {
        const centerLat = {{ $farm->lat ?? -34.6037 }};
        const centerLng = {{ $farm->lng ?? -58.3816 }};
        
        map = L.map('map').setView([centerLat, centerLng], {{ $farm->polygon_coordinates ? 15 : 4 }});
        
        // Capa de OpenStreetMap con vista satelital
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri',
            maxZoom: 18
        }).addTo(map);

        // Agregar capa de etiquetas encima
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_only_labels/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://carto.com/">CARTO</a>',
            maxZoom: 18
        }).addTo(map);

        // Capa para elementos dibujados
        drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        // Control de dibujo
        const drawControl = new L.Control.Draw({
            position: 'topright',
            draw: {
                polyline: false,
                circle: false,
                rectangle: false,
                marker: false,
                circlemarker: false,
                polygon: {
                    allowIntersection: false,
                    shapeOptions: {
                        color: '#FF0000',
                        fillColor: '#FF0000',
                        fillOpacity: 0.35,
                        weight: 2
                    }
                }
            },
            edit: {
                featureGroup: drawnItems,
                remove: true
            }
        });
        map.addControl(drawControl);

        const editToolbar = drawControl?._toolbars?.edit;
        const editHandler = editToolbar?._modes?.edit?.handler;
        const isEditMode = () => !!(editHandler && typeof editHandler.enabled === 'function' && editHandler.enabled());
        editToolbar?._modes?.edit?.handler?.disable();
        editToolbar?._modes?.remove?.handler?.disable();


        applyEditLock = () => {
            if (!activeTarget || !drawnItems) return;
            if (!isEditMode()) return;
            drawnItems.eachLayer((layer) => {
                if (!layer._target || !layer.editing) return;
                const isActive = layer._target.type === activeTarget.type && String(layer._target.id) === String(activeTarget.id);
                if (isActive) {
                    layer.editing.enable();
                } else {
                    layer.editing.disable();
                }
            });
        };

        map.on(L.Draw.Event.EDITSTART, function() {
            applyEditLock();
        });

        map.on(L.Draw.Event.EDITSTOP, function() {
            drawnItems?.eachLayer((layer) => layer.editing && layer.editing.disable());
        });


        const geocoder = L.Control.geocoder({
            defaultMarkGeocode: false,
            placeholder: 'Buscar localidad o provincia',
            geocoder: L.Control.Geocoder.nominatim({
                geocodingQueryParams: {
                    countrycodes: 'ar'
                }
            })
        })
        .on('markgeocode', function(e) {
            const bbox = e.geocode.bbox;
            const bounds = L.latLngBounds(bbox.getSouthEast(), bbox.getNorthWest());
            map.fitBounds(bounds, { padding: [20, 20] });
        });

        geocoder.addTo(map);

        const bounds = [];
        const focusBounds = [];
        const orgFarms = @json($orgFarmsPayload);
        const farmNameLabel = @json($farm->name);
        const clientNameLabel = @json($farm->client?->name);
        const orgLayerGroup = L.layerGroup().addTo(map);

        orgFarms.forEach((farm) => {
            if (!Array.isArray(farm.polygon) || !farm.polygon.length) return;
            const latlngs = farm.polygon.map(coord => [coord.lat, coord.lng]);
            const otherLabel = farm.client_name
                ? `<strong>${farm.client_name}</strong><br>${farm.name || `#${farm.id}`}`
                : `${farm.name || `#${farm.id}`}`;
            const otherPolygon = L.polygon(latlngs, {
                color: '#16A34A',
                fillColor: '#16A34A',
                fillOpacity: 0.25,
                weight: 2,
            }).bindPopup(otherLabel);
            orgLayerGroup.addLayer(otherPolygon);
        });

        // Polígono explotación (rojo)
        @if($farm->polygon_coordinates)
            const farmCoordinates = @json(json_decode($farm->polygon_coordinates, true));
            const farmLatLngs = farmCoordinates.map(coord => [coord.lat, coord.lng]);
            
            currentPolygon = L.polygon(farmLatLngs, {
                color: '#FF0000',
                fillColor: '#FF0000',
                fillOpacity: 0,
                weight: 2
            });
            currentPolygon._target = { type: 'farm', id: null };
            drawnItems.addLayer(currentPolygon);
            bounds.push(currentPolygon.getBounds());
            focusBounds.push(currentPolygon.getBounds());
        @endif


        // Ensure edit mode is off before rendering polygons
        editToolbar?._modes?.edit?.handler?.disable();
        editToolbar?._modes?.remove?.handler?.disable();
        // Polígonos de lotes (color por tipo)
        document.querySelectorAll('[data-lot-polygon]').forEach((input) => {
            if (!input.value) return;
            try {
                const coords = JSON.parse(input.value);
                const latlngs = coords.map(coord => [coord.lat, coord.lng]);
                const lotId = input.dataset.lotPolygon;
                const row = document.querySelector(`[data-lot-row="${lotId}"]`);
                const lotName = row?.querySelector('input[name="name"]')?.value?.trim();
                const color = getLotColor(row);
                const lotPolygon = L.polygon(latlngs, {
                    color,
                    fillColor: color,
                    fillOpacity: 0.25,
                    weight: 2
                }).bindPopup(`<strong>${clientNameLabel || 'Cliente'}</strong><br>${farmNameLabel} - ${lotName || `#${lotId}`}`);
                lotPolygon._target = { type: 'lot', id: lotId };
                lotPolygon.on('click', () => setActiveTarget('lot', lotId));
                drawnItems.addLayer(lotPolygon);
                bounds.push(lotPolygon.getBounds());
            } catch (e) {
                // ignore invalid json
            }
        });

        // Ensure no polygon starts in edit mode
        drawnItems.eachLayer((layer) => {
            if (layer.editing) {
                layer.editing.disable();
            }
        });

        if (focusBounds.length) {
            const group = L.featureGroup(focusBounds.map(b => L.rectangle(b)));
            map.fitBounds(group.getBounds(), { padding: [20, 20] });
        } else if (!bounds.length && navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((pos) => {
                map.setView([pos.coords.latitude, pos.coords.longitude], 13);
            });
        }

        // Cuando se dibuja un nuevo polígono
        map.on(L.Draw.Event.CREATED, function(e) {
            const layer = e.layer;
            const target = activeTarget;

            // Si ya existe un polígono para el target, eliminarlo
            drawnItems.eachLayer((existing) => {
                if (existing._target && existing._target.type === target.type && String(existing._target.id) === String(target.id)) {
                    drawnItems.removeLayer(existing);
                }
            });

            layer._target = { type: target.type, id: target.id };
            if (target.type === 'farm') {
                layer.setStyle({ color: '#FF0000', fillColor: '#FF0000', fillOpacity: 0, weight: 2 });
            } else {
                const row = document.querySelector(`[data-lot-row="${target.id}"]`);
                const lotName = row?.querySelector('input[name="name"]')?.value?.trim();
                const color = getLotColor(row);
                layer.setStyle({ color, fillColor: color, fillOpacity: 0.25, weight: 2 });
                layer.bindPopup(`<strong>${clientNameLabel || 'Cliente'}</strong><br>${farmNameLabel} - ${lotName || `#${target.id}`}`);
                layer.on('click', () => setActiveTarget('lot', target.id));
            }
            drawnItems.addLayer(layer);

            let latlngs = layer.getLatLngs()[0];
            if (target.type === 'lot') {
                latlngs = snapLatLngs(latlngs);
                layer.setLatLngs(latlngs);
                if (!validateLotPolygon(layer)) {
                    drawnItems.removeLayer(layer);
                    const input = document.querySelector(`[data-lot-polygon="${target.id}"]`);
                    if (input) input.value = '';
                    return;
                }
            }
            savePolygonForTarget(latlngs, target);
        });

        // Cuando se edita el polígono
        map.on(L.Draw.Event.EDITED, function(e) {
            e.layers.eachLayer((layer) => {
                if (!layer._target) return;
                let latlngs = layer.getLatLngs()[0];
                if (layer._target.type === 'lot') {
                    latlngs = snapLatLngs(latlngs);
                    layer.setLatLngs(latlngs);
                    if (!validateLotPolygon(layer)) {
                        drawnItems.removeLayer(layer);
                        const input = document.querySelector(`[data-lot-polygon="${layer._target.id}"]`);
                        if (input) input.value = '';
                        return;
                    }
                }
                savePolygonForTarget(latlngs, layer._target);
            });
        });

        // Cuando se elimina el polígono
        map.on(L.Draw.Event.DELETED, function(e) {
            e.layers.eachLayer((layer) => {
                if (!layer._target) return;
                if (layer._target.type === 'farm') {
                    currentPolygon = null;
                    document.getElementById('polygon_coordinates').value = '';
                    document.getElementById('lat').value = '';
                    document.getElementById('lng').value = '';
                } else {
                    const input = document.querySelector(`[data-lot-polygon="${layer._target.id}"]`);
                    if (input) {
                        input.value = '';
                    }
                }
            });
        });
    }

    function clearPolygon() {
        if (!drawnItems) return;
        drawnItems.eachLayer((layer) => {
            if (layer._target && layer._target.type === 'farm') {
                drawnItems.removeLayer(layer);
            }
        });
        currentPolygon = null;
        document.getElementById('polygon_coordinates').value = '';
        document.getElementById('lat').value = '';
        document.getElementById('lng').value = '';
    }

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

    function toggleClientSelect() {
        const groupSelect = document.getElementById('client_group_id');
        const clientSelect = document.getElementById('client_id');
        if (!groupSelect || !clientSelect) return;

        if (groupSelect.value) {
            clientSelect.value = '';
            clientSelect.disabled = true;
            clientSelect.classList.add('bg-gray-100', 'text-gray-500');
        } else {
            clientSelect.disabled = false;
            clientSelect.classList.remove('bg-gray-100', 'text-gray-500');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('client_group_id')?.addEventListener('change', toggleClientSelect);
        toggleClientSelect();

        document.getElementById('map-instructions-btn')?.addEventListener('click', () => {
            document.getElementById('map-instructions-modal')?.classList.remove('hidden');
        });
        document.getElementById('map-instructions-close')?.addEventListener('click', () => {
            document.getElementById('map-instructions-modal')?.classList.add('hidden');
        });
        document.getElementById('map-instructions-close-x')?.addEventListener('click', () => {
            document.getElementById('map-instructions-modal')?.classList.add('hidden');
        });
        document.getElementById('map-instructions-modal')?.addEventListener('click', (e) => {
            if (e.target?.id === 'map-instructions-modal') {
                e.target.classList.add('hidden');
            }
        });

        applyEditLock();
        document.querySelectorAll('[data-lot-row]').forEach((row) => {
            row.querySelectorAll('input[name="is_agricola"], input[name="is_ganadera"], input[name="is_otro"]').forEach((checkbox) => {
                checkbox.addEventListener('change', () => {
                    updateLotColorFromRow(row, row.dataset.lotRow);
                });
            });
        });

        document.getElementById('area-warning-close')?.addEventListener('click', () => {
            document.getElementById('area-warning-modal')?.classList.add('hidden');
        });
        document.getElementById('area-warning-close-x')?.addEventListener('click', () => {
            document.getElementById('area-warning-modal')?.classList.add('hidden');
        });
        document.getElementById('area-warning-modal')?.addEventListener('click', (e) => {
            if (e.target?.id === 'area-warning-modal') {
                e.target.classList.add('hidden');
            }
        });

        try {
            initMap();
            setActiveTarget('farm');
            document.getElementById('farm-polygon-btn')?.addEventListener('click', () => setActiveTarget('farm'));
            document.querySelectorAll('[data-lot-target]').forEach((btn) => {
                btn.addEventListener('click', () => setActiveTarget('lot', btn.dataset.lotTarget));
            });
        } catch (e) {
            console.error('Map init failed', e);
        }

        const remaining = parseFloat(document.querySelector('[data-remaining-has]')?.dataset.remainingHas || '');
        const newLotHasInput = document.getElementById('new-lot-has') || document.querySelector('form[action*=\"/lots\"] input[name=\"has\"]');
        if (newLotHasInput && !newLotHasInput.value && !Number.isNaN(remaining) && remaining > 0) {
            newLotHasInput.value = remaining.toFixed(2);
        }

        const autoArea = document.getElementById('auto_area');
        const hasInput = document.querySelector('[data-has-input]');
        if (autoArea && hasInput) {
            autoArea.addEventListener('change', () => {
                if (autoArea.checked) {
                    const farmLayer = getFarmLayer();
                    if (farmLayer) {
                        const areaHa = calculateHectares(farmLayer.getLatLngs()[0] || []);
                        if (areaHa > 0) {
                            hasInput.value = areaHa.toFixed(2);
                        }
                    }
                }
            });
        }

        document.getElementById('add-lot-btn')?.addEventListener('click', () => {
            const box = document.getElementById('new-lot-inline');
            if (!box) return;
            box.classList.remove('hidden');
            const nameInput = document.getElementById('new-lot-name');
            if (nameInput) {
                nameInput.focus();
            }
            const remainingValue = parseFloat(document.querySelector('[data-remaining-has]')?.dataset.remainingHas || '');
            const hasInput = document.getElementById('new-lot-has');
            if (hasInput && (!hasInput.value || hasInput.value === '0') && !Number.isNaN(remainingValue)) {
                hasInput.value = remainingValue.toFixed(2);
            }
        });
    });
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('input[data-lot-has]').forEach((input) => {
            input.addEventListener('input', () => {
                const normalized = normalizeDecimalInput(input.value);
                if (normalized !== input.value) {
                    input.value = normalized;
                }
            });
        });
    });
</script>
</x-app-layout>
