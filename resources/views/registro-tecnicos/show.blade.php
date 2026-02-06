<x-app-layout>
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Registro Técnico {{ $registroTecnico->code }}</h1>
                @php
                    $statusClass = match($registroTecnico->status) {
                        'abierto' => 'bg-green-100 text-green-800 ring-green-200',
                        'en_proceso' => 'bg-amber-100 text-amber-800 ring-amber-200',
                        'cerrado' => 'bg-blue-100 text-blue-800 ring-blue-200',
                        'cancelado' => 'bg-red-100 text-red-800 ring-red-200',
                        default => 'bg-gray-100 text-gray-700 ring-gray-200',
                    };
                @endphp
                <p class="text-sm text-gray-500">
                    Estado:
                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusClass }}">
                        {{ $statusLabels[$registroTecnico->status] ?? ucfirst($registroTecnico->status) }}
                    </span>
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('registro-tecnicos.index') }}"
                   class="inline-flex items-center rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                    Volver al talonario
                </a>
                @can('update', $registroTecnico)
                    <a href="{{ route('registro-tecnicos.edit', $registroTecnico) }}"
                       class="inline-flex items-center rounded-lg bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-300">
                        Editar
                    </a>
                @endcan
                @if($registroTecnico->status !== 'cancelado')
                    <a href="{{ route('registro-tecnicos.work-orders.create', $registroTecnico) }}"
                       class="inline-flex items-center rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                        Crear OT
                    </a>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl p-6 space-y-4">
                <div>
                    <p class="text-xs uppercase text-gray-400">Cliente</p>
                    <p class="text-sm text-gray-900">{{ $registroTecnico->client?->name ?? 'Pendiente' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-gray-400">Explotación</p>
                    <p class="text-sm text-gray-900">{{ $registroTecnico->farm?->name ?? 'Pendiente' }}</p>
                </div>
                @if($registroTecnico->farm?->clientGroup)
                    <div>
                        <p class="text-xs uppercase text-gray-400">Grupo de Clientes</p>
                        <p class="text-sm text-gray-900">{{ $registroTecnico->farm->clientGroup->name }}</p>
                        <div class="mt-1 text-xs text-gray-600 space-y-0.5">
                            @foreach($registroTecnico->farm->clientGroup->members as $member)
                                <div>{{ $member->client?->name ?? '—' }} ({{ number_format((float) $member->percentage, 2) }}%)</div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div>
                    <p class="text-xs uppercase text-gray-400">Lote</p>
                    <p class="text-sm text-gray-900">{{ $registroTecnico->lot?->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-gray-400">Objetivos</p>
                    <p class="text-sm text-gray-900">
                        {{ $registroTecnico->objectives ? implode(', ', array_map('ucfirst', $registroTecnico->objectives)) : '—' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs uppercase text-gray-400">Nota</p>
                    <p class="text-sm text-gray-900">{{ $registroTecnico->note ?: '—' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-gray-400">Creado por</p>
                    <p class="text-sm text-gray-900">{{ $registroTecnico->created_by ?? '—' }}</p>
                </div>

                @if(!empty($registroTecnico->photos))
                    <div>
                        <p class="text-xs uppercase text-gray-400">Fotos</p>
                        <div class="mt-2 flex flex-wrap gap-2" id="photo-grid">
                            @foreach($registroTecnico->photos as $photo)
                                <button type="button" class="photo-thumb" data-photo-src="{{ asset('storage/' . $photo) }}">
                                    <img src="{{ asset('storage/' . $photo) }}" alt="Foto" class="h-20 w-20 rounded object-cover">
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($registroTecnico->audio_path)
                    <div>
                        <p class="text-xs uppercase text-gray-400">Audio</p>
                        <audio class="mt-2 w-full" controls src="{{ asset('storage/' . $registroTecnico->audio_path) }}"></audio>
                    </div>
                @endif

                @if($registroTecnico->workOrders->count())
                    <div>
                        <p class="text-xs uppercase text-gray-400">OT vinculadas</p>
                        <div class="mt-2 space-y-1">
                            @foreach($registroTecnico->workOrders as $order)
                                <a href="{{ route('work-orders.show', $order) }}" class="text-sm text-green-600 hover:text-green-800">
                                    {{ $order->code ?? ('OT #' . $order->id) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-2">Ubicación</h2>
                <div id="map" class="w-full h-96 rounded border border-gray-200" style="height:420px;"></div>
                <div class="mt-3 text-xs text-gray-500">
                    Coordenadas: {{ $registroTecnico->lat && $registroTecnico->lng ? $registroTecnico->lat . ', ' . $registroTecnico->lng : 'sin seleccionar' }}
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const hasCoords = {{ $registroTecnico->lat && $registroTecnico->lng ? 'true' : 'false' }};
            const lat = {{ $registroTecnico->lat ?? -34.6037 }};
            const lng = {{ $registroTecnico->lng ?? -58.3816 }};
            const zoom = hasCoords ? 14 : 4;

            const map = L.map('map', { dragging: true, scrollWheelZoom: true }).setView([lat, lng], zoom);
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

            const malvinasMask = L.circleMarker([-51.7, -58.9], {
                radius: 32,
                color: '#ffffff',
                fillColor: '#ffffff',
                fillOpacity: 1,
                weight: 0,
                interactive: false
            }).addTo(map);

            const malvinasLabel = L.marker([-51.7, -59.0], {
                interactive: false,
                icon: L.divIcon({
                    className: 'rt-map-label',
                    html: '<div style="font-weight:600;color:#0f172a;background:rgba(255,255,255,0.95);padding:2px 6px;border-radius:6px;border:1px solid rgba(15,23,42,0.12);font-size:12px;">Islas Malvinas</div>'
                })
            }).addTo(map);

            const farmsGeo = @json($farmsMapPayload ?? []);
            const bounds = [];
            let focusBounds = null;
            farmsGeo.forEach((farm) => {
                if (Array.isArray(farm.polygon) && farm.polygon.length) {
                    const latlngs = farm.polygon.map(p => [p.lat, p.lng]);
                    const poly = L.polygon(latlngs, { color: '#16a34a', weight: 2, fillOpacity: 0.08 });
                    poly.addTo(map).bindPopup(farm.name || 'Explotación');
                    bounds.push(poly.getBounds());
                }
                if (Array.isArray(farm.lots)) {
                    farm.lots.forEach((lot) => {
                        if (!Array.isArray(lot.polygon) || !lot.polygon.length) return;
                        const latlngs = lot.polygon.map(p => [p.lat, p.lng]);
                        const poly = L.polygon(latlngs, { color: '#0284c7', weight: 2, fillOpacity: 0.12 });
                        poly.addTo(map).bindPopup(`${farm.name || 'Explotación'} - ${lot.name || 'Lote'}`);
                        bounds.push(poly.getBounds());
                        if ({{ (int) ($registroTecnico->lot_id ?? 0) }} === lot.id) {
                            focusBounds = poly.getBounds();
                            poly.setStyle({ color: '#1d4ed8', weight: 4, fillOpacity: 0.22 });
                        }
                    });
                }
            });

            if (hasCoords) {
                const marker = L.marker([lat, lng]).addTo(map);
                bounds.push(marker.getLatLng());
            }

            if (focusBounds) {
                map.fitBounds(focusBounds, { padding: [20, 20] });
            } else if (bounds.length) {
                const group = L.featureGroup(bounds.map(b => (b instanceof L.LatLng) ? L.marker(b) : L.rectangle(b, { opacity: 0, fillOpacity: 0 })));
                map.fitBounds(group.getBounds(), { padding: [20, 20] });
            }
        });
    </script>

    <style>
        #map {
            position: relative;
            z-index: 0;
        }
        .leaflet-pane,
        .leaflet-control {
            z-index: 0;
        }
    </style>

    <div id="photo-modal" class="hidden fixed inset-0 z-[2147483647] flex items-center justify-center bg-black/80 backdrop-blur-sm p-6">
        <div class="relative w-full max-w-4xl">
            <div class="absolute -top-8 left-0 right-0 text-center text-xs text-gray-300">Click fuera o ESC para cerrar</div>
            <div class="rounded-2xl bg-white shadow-2xl ring-1 ring-black/10 p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-600" id="photo-counter">1/1</div>
                    <div class="flex items-center gap-2">
                        <button type="button" id="photo-modal-close" class="h-11 w-11 rounded-full bg-gray-100 text-gray-700 hover:bg-gray-200" aria-label="Cerrar">
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <button type="button" id="photo-prev" class="h-11 w-11 rounded-full bg-gray-100 text-gray-700 hover:bg-gray-200" aria-label="Anterior">
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button type="button" id="photo-next" class="h-11 w-11 rounded-full bg-gray-100 text-gray-700 hover:bg-gray-200" aria-label="Siguiente">
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex items-center justify-center overflow-hidden rounded-xl bg-gray-50" style="max-height:60vh;">
                    <img id="photo-modal-img" src="" alt="Foto ampliada" class="max-h-[60vh] max-w-[80vw] object-contain">
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('photo-modal');
            const modalImg = document.getElementById('photo-modal-img');
            const closeBtn = document.getElementById('photo-modal-close');
            const prevBtn = document.getElementById('photo-prev');
            const nextBtn = document.getElementById('photo-next');
            const counter = document.getElementById('photo-counter');

            const photos = Array.from(document.querySelectorAll('.photo-thumb'))
                .map(btn => btn.getAttribute('data-photo-src'))
                .filter(Boolean);

            let currentIndex = 0;
            function updatePhoto() {
                if (!photos.length) return;
                modalImg.src = photos[currentIndex];
                if (counter) {
                    counter.textContent = `${currentIndex + 1}/${photos.length}`;
                }
            }

            function openAt(index) {
                currentIndex = index;
                updatePhoto();
                modal.classList.remove('hidden');
            }

            document.querySelectorAll('.photo-thumb').forEach((btn, idx) => {
                btn.addEventListener('click', () => openAt(idx));
            });

            prevBtn?.addEventListener('click', () => {
                if (!photos.length) return;
                currentIndex = (currentIndex - 1 + photos.length) % photos.length;
                updatePhoto();
            });

            nextBtn?.addEventListener('click', () => {
                if (!photos.length) return;
                currentIndex = (currentIndex + 1) % photos.length;
                updatePhoto();
            });

            closeBtn?.addEventListener('click', () => {
                modal.classList.add('hidden');
                modalImg.src = '';
            });

            modal?.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    modalImg.src = '';
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                    modalImg.src = '';
                }
                if (e.key === 'ArrowLeft' && !modal.classList.contains('hidden')) {
                    prevBtn?.click();
                }
                if (e.key === 'ArrowRight' && !modal.classList.contains('hidden')) {
                    nextBtn?.click();
                }
            });
        });
    </script>
</x-app-layout>
