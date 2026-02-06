<x-app-layout>
<div class="max-w-7xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-6">Detalle de Explotación</h1>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Información de la explotación -->
        <div class="bg-white p-6 rounded shadow space-y-4">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Información</h2>
            
            <div>
                <span class="font-semibold text-gray-700">Cliente:</span>
                <span class="text-gray-900">{{ $farm->client->number }} - {{ $farm->client->name }}</span>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Nombre:</span>
                <span class="text-gray-900">{{ $farm->name }}</span>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Hectáreas (Has):</span>
                <span class="text-gray-900">{{ number_format($farm->has, 2) }}</span>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Distancia al Poblado:</span>
                <span class="text-gray-900">{{ $farm->distancia_poblado ? number_format($farm->distancia_poblado, 0) . ' m' : '-' }}</span>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Estado:</span>
                <span class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $farm->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $farm->status ? 'Activo' : 'Inactivo' }}
                </span>
            </div>
            
            <div class="flex space-x-2 mt-6">
                <a href="{{ route('farms.edit', $farm) }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Editar</a>
                <a href="{{ route('clients.show', $farm->client_id) }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Volver</a>
            </div>
        </div>

        <div class="bg-white p-6 rounded shadow space-y-4">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Lotes</h2>
            @if($farm->lots->isEmpty())
                <p class="text-sm text-gray-500">No hay lotes registrados.</p>
            @else
                <div class="space-y-2">
                    @foreach($farm->lots as $lot)
                        <div class="border border-gray-200 rounded-lg p-3 text-sm">
                            <div class="font-semibold text-gray-800">{{ $lot->name }}</div>
                            <div class="text-gray-600">Has: {{ number_format($lot->has, 2) }}</div>
                            <div class="text-gray-600">
                                {{ $lot->is_agricola ? 'Agrícola' : 'No agrícola' }} ·
                                {{ $lot->is_ganadera ? 'Ganadera' : 'No ganadera' }} ·
                                {{ $lot->is_otro ? 'Otro' : 'No otro' }}
                            </div>
                            <div class="text-gray-600">
                                Alertas Vigía: {{ $lot->vigia_alerts_enabled ? 'Habilitadas' : 'Deshabilitadas' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Mapa con polígono -->
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Ubicación en Mapa</h2>
            <div id="map" class="w-full rounded border border-gray-300" style="height:520px;"></div>
            @if(!$farm->polygon_coordinates)
                <p class="mt-2 text-sm text-gray-600">No hay polígono marcado. <a href="{{ route('farms.edit', $farm) }}" class="text-green-600 hover:underline">Editar para agregar.</a></p>
            @else
                <div class="mt-4 p-3 bg-gray-50 rounded border border-gray-200">
                    <p class="text-sm text-gray-700 mb-2 font-medium">📍 Compartir ubicación:</p>
                    <a href="https://www.google.com/maps?q={{ $farm->lat }},{{ $farm->lng }}" 
                       target="_blank" 
                       class="inline-flex items-center text-sm text-green-600 hover:text-green-800 hover:underline">
                        Abrir en Google Maps →
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>

<script>
    let map;
    let polygon = null;

    function initMap() {
        const centerLat = {{ $farm->lat ?? -34.6037 }};
        const centerLng = {{ $farm->lng ?? -58.3816 }};
        
        map = L.map('map').setView([centerLat, centerLng], {{ $farm->polygon_coordinates ? 15 : 4 }});
        
        // Capa de OpenStreetMap con vista satelital
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
            maxZoom: 18
        }).addTo(map);

        // Agregar capa de etiquetas encima
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_only_labels/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://carto.com/">CARTO</a>',
            maxZoom: 18
        }).addTo(map);

        @if($farm->polygon_coordinates)
            const coordinates = @json(json_decode($farm->polygon_coordinates, true));
            
            const latlngs = coordinates.map(coord => [coord.lat, coord.lng]);
            
            polygon = L.polygon(latlngs, {
                color: '#FF0000',
                fillColor: '#FF0000',
                fillOpacity: 0.35,
                weight: 2
            }).addTo(map);
            
            // Centrar el mapa en el polígono
            map.fitBounds(polygon.getBounds());
        @endif
    }

    document.addEventListener('DOMContentLoaded', initMap);
</script>
</x-app-layout>

