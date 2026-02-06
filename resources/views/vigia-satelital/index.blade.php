<x-app-layout>
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Vigía Satelital</h1>
                <p class="text-sm text-gray-500">Mapas, alertas y acciones sobre tus lotes.</p>
            </div>
            <a href="{{ route('vigia-satelital.config') }}"
               class="inline-flex items-center rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                Configuración
            </a>
        </div>

        <div class="mb-6 flex flex-wrap gap-2">
            <a href="{{ route('vigia-satelital.mapas') }}"
               class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium {{ $section === 'mapas' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Vigía Satelital – Mapas
            </a>
            <a href="{{ route('vigia-satelital.alertas') }}"
               class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium {{ $section === 'alertas' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Vigía Satelital – Alertas
            </a>
            <a href="{{ route('vigia-satelital.acciones') }}"
               class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium {{ $section === 'acciones' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Vigía Satelital – Acciones
            </a>
        </div>

        @if($section === 'mapas')
            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Mapas (timeline / índices)</h2>
                <p class="text-sm text-gray-600 mb-4">Vista satelital con límites y lotes cargados.</p>
                <div id="vigia-map" class="w-full h-96 rounded border border-gray-200" style="height:520px;"></div>
                @if(empty($farmsGeo) || $farmsGeo->isEmpty())
                    <p class="mt-3 text-xs text-gray-500">Aún no hay polígonos cargados para mostrar.</p>
                @endif
                <p class="mt-3 text-xs text-gray-500">Datos satelitales: Copernicus Sentinel-2 (datos abiertos).</p>
            </div>
        @elseif($section === 'alertas')
            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Alertas (cambios / manchas)</h2>
                <p class="text-sm text-gray-600">Se listarán alertas semanales por explotación según tus umbrales.</p>
                <div class="mt-4 rounded-lg border border-dashed border-gray-300 p-6 text-sm text-gray-500">
                    Próximamente: listado de alertas y mapa con manchas.
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2">Registro Técnico</h2>
                    <p class="text-sm text-gray-600 mb-4">Crear un RT desde un hallazgo satelital.</p>
                    <a href="{{ route('registro-tecnicos.create') }}" class="inline-flex items-center rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                        Crear Registro Técnico
                    </a>
                </div>
                <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2">Orden de Trabajo</h2>
                    <p class="text-sm text-gray-600 mb-4">Crear una OT vinculada a un RT.</p>
                    <a href="{{ route('work-orders.create') }}" class="inline-flex items-center rounded-lg bg-purple-600 px-4 py-2 text-sm font-medium text-white hover:bg-purple-700">
                        Crear OT
                    </a>
                </div>
            </div>
        @endif
    </div>

    @if($section === 'mapas')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const farms = @json($farmsGeo ?? []);
                const fallback = [-34.6037, -58.3816];
                let center = fallback;

                for (const farm of farms) {
                    if (farm.lat && farm.lng) {
                        center = [parseFloat(farm.lat), parseFloat(farm.lng)];
                        break;
                    }
                    if (Array.isArray(farm.polygon) && farm.polygon.length) {
                        center = [farm.polygon[0].lat, farm.polygon[0].lng];
                        break;
                    }
                }

                const map = L.map('vigia-map').setView(center, farms.length ? 12 : 4);
                const baseOSM = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap'
                });
                const baseSat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    maxZoom: 19,
                    attribution: 'Tiles &copy; Esri'
                });
                const labelsOverlay = L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
                    maxZoom: 19,
                    attribution: 'Labels &copy; Esri'
                });
                baseSat.addTo(map);
                labelsOverlay.addTo(map);
                L.control.layers({
                    'Mapa': baseOSM,
                    'Satélite': baseSat
                }, {
                    'Límites y localidades': labelsOverlay
                }, { position: 'topright' }).addTo(map);

                const bounds = [];
                farms.forEach(farm => {
                    if (Array.isArray(farm.polygon) && farm.polygon.length) {
                        const latlngs = farm.polygon.map(p => [p.lat, p.lng]);
                        const poly = L.polygon(latlngs, { color: '#16a34a', weight: 2, fillOpacity: 0.08 });
                        poly.addTo(map).bindPopup(farm.name || 'Explotación');
                        bounds.push(...latlngs);
                    } else if (farm.lat && farm.lng) {
                        const marker = L.marker([farm.lat, farm.lng]).addTo(map);
                        marker.bindPopup(farm.name || 'Explotación');
                        bounds.push([farm.lat, farm.lng]);
                    }
                });

                if (bounds.length) {
                    map.fitBounds(bounds, { padding: [20, 20] });
                }
            });
        </script>
    @endif
</x-app-layout>
