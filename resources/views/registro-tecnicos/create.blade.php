<x-app-layout>
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Nuevo Registro Técnico</h1>
            <p class="text-sm text-gray-500">Marca un pin en el mapa o completa los datos manualmente.</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4">
                <ul class="list-disc list-inside text-sm text-red-800">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('registro-tecnicos.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @csrf

            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl p-6 space-y-4">
                <div>
                    <label for="client_selector" class="block text-sm font-medium text-gray-700">Cliente/Grupo</label>
                    <select name="client_selector" id="client_selector" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
                        <option value="">Seleccionar cliente o grupo</option>
                        <optgroup label="Clientes">
                            @foreach($clients as $client)
                                @php($clientValue = 'client:' . $client->id)
                                <option value="{{ $clientValue }}" {{ old('client_selector') === $clientValue || (!old('client_selector') && old('client_id') == $client->id) ? 'selected' : '' }}>
                                    {{ $client->number }} - {{ $client->name }}
                                </option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Grupos">
                            @foreach($clientGroups as $group)
                                @php($groupValue = 'group:' . $group->id)
                                <option value="{{ $groupValue }}" {{ old('client_selector') === $groupValue ? 'selected' : '' }}>
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                    <input type="hidden" name="client_id" id="client_id" value="{{ old('client_id') }}">
                </div>

                <div>
                    <label for="farm_id" class="block text-sm font-medium text-gray-700">Explotación</label>
                    <select name="farm_id" id="farm_id" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
                        <option value="">Seleccionar explotación</option>
                        @foreach($farms as $farm)
                            <option value="{{ $farm->id }}" data-client="{{ $farm->client_id }}" data-group="{{ $farm->client_group_id }}" {{ old('farm_id') == $farm->id ? 'selected' : '' }}>
                                {{ $farm->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Si marcas un pin dentro de un polígono, se completará automáticamente.</p>
                </div>

                <div>
                    <label for="lot_id" class="block text-sm font-medium text-gray-700">Lote</label>
                    <select name="lot_id" id="lot_id" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
                        <option value="">Seleccionar lote</option>
                        @foreach($farms as $farm)
                            @foreach($farm->lots as $lot)
                                <option value="{{ $lot->id }}" data-client="{{ $farm->client_id }}" data-group="{{ $farm->client_group_id }}" data-farm="{{ $farm->id }}" {{ old('lot_id') == $lot->id ? 'selected' : '' }}>
                                    {{ $farm->name }} - {{ $lot->name }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Si el pin cae dentro del polígono de un lote, se completará automáticamente.</p>
                </div>

                <div>
                    <p class="block text-sm font-medium text-gray-700 mb-2">Objetivo</p>
                    <div class="space-y-2">
                        @foreach($objectiveOptions as $value => $label)
                            <label class="flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="objectives[]" value="{{ $value }}" class="rounded border-gray-300 text-green-600"
                                    {{ in_array($value, old('objectives', [])) ? 'checked' : '' }}>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label for="note" class="block text-sm font-medium text-gray-700">Nota / Observación</label>
                    <div class="mt-1 flex items-start gap-2">
                        <textarea name="note" id="note" rows="4" class="block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">{{ old('note') }}</textarea>
                        <button type="button" id="voiceBtn" class="inline-flex items-center rounded-lg bg-gray-200 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-300">
                            🎙️ Voz
                        </button>
                    </div>
                    <p id="voiceStatus" class="mt-1 text-xs text-gray-500"></p>
                </div>

                <div>
                    <label for="photos" class="block text-sm font-medium text-gray-700">Fotos</label>
                    <input type="file" name="photos[]" id="photos" multiple accept="image/*" class="mt-1 block w-full text-sm text-gray-500">
                </div>

                <div>
                    <label for="audio" class="block text-sm font-medium text-gray-700">Audio</label>
                    <input type="file" name="audio" id="audio" accept="audio/*" class="mt-1 block w-full text-sm text-gray-500">
                </div>

                <input type="hidden" name="lat" id="lat" value="{{ old('lat') }}">
                <input type="hidden" name="lng" id="lng" value="{{ old('lng') }}">

                <div class="flex items-center gap-3">
                    <a href="{{ route('registro-tecnicos.index') }}" class="inline-flex items-center rounded-lg bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-300">Cancelar</a>
                    <button type="submit" class="inline-flex items-center rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">Crear</button>
                </div>
            </div>

            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl p-6">
                <div class="mb-2 flex items-center justify-between gap-3">
                    <h2 class="text-lg font-medium text-gray-900">Ubicación</h2>
                    <div class="relative w-64">
                        <input id="farmSearch" type="text" placeholder="Buscar explotación o localidad" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-green-500 focus:ring-green-500">
                        <div id="farmSearchResults" class="hidden absolute z-20 mt-1 w-full max-h-56 overflow-auto rounded-lg border border-gray-200 bg-white shadow-lg"></div>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mb-4">Haz clic en el mapa para marcar el pin.</p>
                <div id="map" class="w-full h-96 rounded border border-gray-200" style="height:420px;"></div>
                <div class="mt-3 flex items-center gap-3">
                    <button type="button" id="locateBtn" class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        Usar mi ubicación
                    </button>
                    <span id="locateStatus" class="text-xs text-gray-500"></span>
                </div>
                <div class="mt-3 text-xs text-gray-500">
                    Coordenadas: <span id="coordText">{{ old('lat') && old('lng') ? old('lat') . ', ' . old('lng') : 'sin seleccionar' }}</span>
                </div>
            </div>
        </form>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const latInput = document.getElementById('lat');
            const lngInput = document.getElementById('lng');
            const coordText = document.getElementById('coordText');
            const clientSelector = document.getElementById('client_selector');
            const clientIdInput = document.getElementById('client_id');
            const farmSelect = document.getElementById('farm_id');
            const lotSelect = document.getElementById('lot_id');
            const locateBtn = document.getElementById('locateBtn');
            const locateStatus = document.getElementById('locateStatus');
            const voiceBtn = document.getElementById('voiceBtn');
            const voiceStatus = document.getElementById('voiceStatus');
            const noteInput = document.getElementById('note');

            const startLat = latInput.value ? parseFloat(latInput.value) : -34.6037;
            const startLng = lngInput.value ? parseFloat(lngInput.value) : -58.3816;
            const startZoom = latInput.value && lngInput.value ? 14 : 4;

            const map = L.map('map').setView([startLat, startLng], startZoom);
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

            let marker = null;
            if (latInput.value && lngInput.value) {
                marker = L.marker([startLat, startLng]).addTo(map);
            }

            const farmsGeo = @json($farmsMapPayload);
            const farmLayers = new Map();
            const lotLayers = new Map();
            const farmBounds = [];
            farmsGeo.forEach((farm) => {
                if (Array.isArray(farm.polygon) && farm.polygon.length) {
                    const latlngs = farm.polygon.map(p => [p.lat, p.lng]);
                    const poly = L.polygon(latlngs, { color: '#16a34a', weight: 2, fillOpacity: 0.08 });
                    poly.addTo(map).bindPopup(farm.name || 'Explotación');
                    farmBounds.push(poly.getBounds());
                    poly.on('click', () => {
                        const center = poly.getBounds().getCenter();
                        setLocation(center.lat, center.lng, 16);
                        if (typeof setSelectorFromFarm === 'function') {
                            setSelectorFromFarm(farm);
                        }
                        if (farmSelect) {
                            farmSelect.value = String(farm.id);
                        }
                        if (lotSelect) {
                            lotSelect.value = '';
                        }
                    });
                    farmLayers.set(farm.id, poly);
                }
                if (Array.isArray(farm.lots)) {
                    farm.lots.forEach((lot) => {
                        if (!Array.isArray(lot.polygon) || !lot.polygon.length) return;
                        const latlngs = lot.polygon.map(p => [p.lat, p.lng]);
                        const poly = L.polygon(latlngs, { color: '#0284c7', weight: 2, fillOpacity: 0.12 });
                        poly.addTo(map).bindPopup(`${farm.name || 'Explotación'} - ${lot.name || 'Lote'}`);
                        poly.on('click', () => {
                            const center = poly.getBounds().getCenter();
                            setLocation(center.lat, center.lng, 16);
                            if (typeof setSelectorFromFarm === 'function') {
                                setSelectorFromFarm(farm);
                            }
                            if (farmSelect) {
                                farmSelect.value = String(farm.id);
                            }
                            if (lotSelect) {
                                lotSelect.value = String(lot.id);
                            }
                            if (typeof filterLots === 'function') {
                                filterLots();
                            }
                        });
                        lotLayers.set(lot.id, poly);
                        farmBounds.push(poly.getBounds());
                    });
                }
            });

            if (farmBounds.length) {
                const group = L.featureGroup(farmBounds.map(b => L.rectangle(b, { opacity: 0, fillOpacity: 0 })));
                map.fitBounds(group.getBounds(), { padding: [20, 20] });
            }

            const searchInput = document.getElementById('farmSearch');
            const searchResults = document.getElementById('farmSearchResults');
            function renderResults(items) {
                if (!searchResults) return;
                if (!items.length) {
                    searchResults.classList.add('hidden');
                    searchResults.innerHTML = '';
                    return;
                }
                searchResults.innerHTML = items.map(item => {
                    const loc = item.localidad ? ` · ${item.localidad}` : '';
                    return `<button type="button" data-farm-id="${item.id}" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50">${item.name}${loc}</button>`;
                }).join('');
                searchResults.classList.remove('hidden');
            }

            function highlightFarm(id) {
                farmLayers.forEach((layer, key) => {
                    if (key === id) {
                        layer.setStyle({ color: '#16a34a', weight: 3, fillOpacity: 0.12 });
                    } else {
                        layer.setStyle({ color: '#16a34a', weight: 2, fillOpacity: 0.08 });
                    }
                });
            }

            function highlightLot(id) {
                lotLayers.forEach((layer, key) => {
                    if (key === id) {
                        layer.setStyle({ color: '#0284c7', weight: 3, fillOpacity: 0.18 });
                    } else {
                        layer.setStyle({ color: '#0284c7', weight: 2, fillOpacity: 0.12 });
                    }
                });
            }

            searchInput?.addEventListener('input', () => {
                const term = searchInput.value.trim().toLowerCase();
                if (!term) {
                    renderResults([]);
                    return;
                }
                const results = farmsGeo.filter(f => {
                    return (f.name || '').toLowerCase().includes(term) || (f.localidad || '').toLowerCase().includes(term);
                }).slice(0, 10);
                renderResults(results);
            });

            searchResults?.addEventListener('click', (e) => {
                const btn = e.target.closest('button[data-farm-id]');
                if (!btn) return;
                const id = parseInt(btn.dataset.farmId, 10);
                const farm = farmsGeo.find(f => f.id === id);
                if (farm) {
                    if (farmLayers.has(id)) {
                        map.fitBounds(farmLayers.get(id).getBounds(), { padding: [20, 20] });
                        highlightFarm(id);
                        const center = farmLayers.get(id).getBounds().getCenter();
                        setLocation(center.lat, center.lng, 16);
                    } else if (farm.lat && farm.lng) {
                        map.setView([farm.lat, farm.lng], 15);
                        setLocation(farm.lat, farm.lng, 15);
                    }
                    const farmSelect = document.getElementById('farm_id');
                    if (farmSelect) {
                        farmSelect.value = String(id);
                    }
                    if (typeof setSelectorFromFarm === 'function') {
                        setSelectorFromFarm(farm);
                        if (farmSelect) {
                            farmSelect.value = String(id);
                        }
                    }
                    if (lotSelect) {
                        lotSelect.value = '';
                    }
                    if (typeof filterLots === 'function') {
                        filterLots();
                    }
                }
                renderResults([]);
                searchInput.value = '';
            });

            function isPointInsidePoly(lat, lng, polygon) {
                let inside = false;
                for (let i = 0, j = polygon.length - 1; i < polygon.length; j = i++) {
                    const xi = polygon[i].lat, yi = polygon[i].lng;
                    const xj = polygon[j].lat, yj = polygon[j].lng;
                    const intersect = ((yi > lng) !== (yj > lng)) &&
                        (lat < (xj - xi) * (lng - yi) / ((yj - yi) || 1e-12) + xi);
                    if (intersect) inside = !inside;
                }
                return inside;
            }

            function findLotById(id) {
                for (const farm of farmsGeo) {
                    const lot = (farm.lots || []).find(l => String(l.id) === String(id));
                    if (lot) {
                        return { farm, lot };
                    }
                }
                return null;
            }

            function updateClientFarmByPoint(lat, lng) {
                let matched = null;
                let matchedLot = null;
                farmsGeo.forEach((farm) => {
                    if (matchedLot || !Array.isArray(farm.lots)) return;
                    farm.lots.forEach((lot) => {
                        if (matchedLot || !Array.isArray(lot.polygon) || !lot.polygon.length) return;
                        if (isPointInsidePoly(lat, lng, lot.polygon)) {
                            matched = farm;
                            matchedLot = lot;
                        }
                    });
                });

                if (!matchedLot) {
                    farmsGeo.forEach((farm) => {
                        if (matched || !Array.isArray(farm.polygon) || !farm.polygon.length) return;
                        if (isPointInsidePoly(lat, lng, farm.polygon)) {
                            matched = farm;
                        }
                    });
                }

                if (matched) {
                    if (typeof setSelectorFromFarm === 'function') {
                        setSelectorFromFarm(matched);
                    }
                    if (farmSelect) {
                        farmSelect.value = String(matched.id);
                    }
                    if (lotSelect) {
                        lotSelect.value = matchedLot ? String(matchedLot.id) : '';
                    }
                    if (typeof filterLots === 'function') {
                        filterLots();
                    }
                    if (matchedLot) {
                        highlightLot(matchedLot.id);
                    }
                } else {
                    if (clientSelector) {
                        clientSelector.value = '';
                    }
                    if (clientIdInput) {
                        clientIdInput.value = '';
                    }
                    if (farmSelect) {
                        farmSelect.value = '';
                    }
                    if (lotSelect) {
                        lotSelect.value = '';
                    }
                    if (typeof filterFarms === 'function') {
                        filterFarms();
                    }
                    if (typeof filterLots === 'function') {
                        filterLots();
                    }
                }
            }

            map.on('click', (e) => {
                const { lat, lng } = e.latlng;
                latInput.value = lat.toFixed(7);
                lngInput.value = lng.toFixed(7);
                coordText.textContent = `${lat.toFixed(7)}, ${lng.toFixed(7)}`;

                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng]).addTo(map);
                }

                updateClientFarmByPoint(lat, lng);
            });

            function setLocation(lat, lng, zoom = 16) {
                const fixedLat = parseFloat(lat).toFixed(7);
                const fixedLng = parseFloat(lng).toFixed(7);
                latInput.value = fixedLat;
                lngInput.value = fixedLng;
                coordText.textContent = `${fixedLat}, ${fixedLng}`;
                map.setView([fixedLat, fixedLng], zoom);
                if (marker) {
                    marker.setLatLng([fixedLat, fixedLng]);
                } else {
                    marker = L.marker([fixedLat, fixedLng]).addTo(map);
                }
            }

            function requestLocation() {
                locateStatus.textContent = 'Buscando ubicación...';
                if (!navigator.geolocation) {
                    locateStatus.textContent = 'Geolocalización no disponible.';
                    return;
                }
                if (!window.isSecureContext) {
                    locateStatus.textContent = 'Requiere HTTPS para usar GPS.';
                    return;
                }
                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        setLocation(pos.coords.latitude, pos.coords.longitude);
                        locateStatus.textContent = 'Ubicación actualizada.';
                        setTimeout(() => { locateStatus.textContent = ''; }, 3000);
                    },
                    () => {
                        locateStatus.textContent = 'No se pudo obtener la ubicación.';
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            }

            locateBtn?.addEventListener('click', requestLocation);

            const isMobile = window.matchMedia('(max-width: 768px)').matches;
            if (isMobile && (!latInput.value || !lngInput.value)) {
                requestLocation();
            }

            let recognition = null;
            let isRecording = false;
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (SpeechRecognition) {
                recognition = new SpeechRecognition();
                recognition.lang = 'es-AR';
                recognition.interimResults = true;
                recognition.continuous = false;

                recognition.onresult = (event) => {
                    let transcript = '';
                    for (let i = event.resultIndex; i < event.results.length; i++) {
                        if (event.results[i].isFinal) {
                            transcript += event.results[i][0].transcript;
                        }
                    }
                    if (transcript.trim()) {
                        noteInput.value = (noteInput.value ? noteInput.value + ' ' : '') + transcript.trim();
                    }
                };

                recognition.onerror = (e) => {
                    voiceStatus.textContent = e?.error === 'not-allowed'
                        ? 'Permiso de micrófono denegado.'
                        : 'No se pudo usar el dictado.';
                    isRecording = false;
                };

                recognition.onend = () => {
                    if (isRecording) {
                        setTimeout(() => {
                            try { recognition.start(); } catch (e) { /* noop */ }
                        }, 200);
                    } else {
                        voiceBtn.textContent = '🎙️ Voz';
                        voiceStatus.textContent = '';
                    }
                };
            } else if (voiceBtn) {
                voiceBtn.disabled = true;
                voiceStatus.textContent = 'Dictado no disponible en este navegador.';
            }

            voiceBtn?.addEventListener('click', () => {
                if (!recognition) return;
                if (!isRecording) {
                    isRecording = true;
                    voiceBtn.textContent = '⏹️ Detener';
                    voiceStatus.textContent = 'Escuchando...';
                    try { recognition.start(); } catch (e) { /* noop */ }
                } else {
                    isRecording = false;
                    voiceBtn.textContent = '🎙️ Voz';
                    voiceStatus.textContent = '';
                    recognition.stop();
                }
            });

            function getSelection() {
                const value = clientSelector?.value || '';
                if (value.startsWith('client:')) {
                    return { clientId: value.replace('client:', ''), groupId: null };
                }
                if (value.startsWith('group:')) {
                    return { clientId: null, groupId: value.replace('group:', '') };
                }
                return { clientId: null, groupId: null };
            }

            function applySelectorToHidden() {
                if (!clientIdInput) return;
                const { clientId } = getSelection();
                clientIdInput.value = clientId || '';
            }

            function setSelectorFromFarm(farm) {
                if (!clientSelector || !farm) return;
                if (farm.client_group_id) {
                    clientSelector.value = `group:${farm.client_group_id}`;
                } else if (farm.client_id) {
                    clientSelector.value = `client:${farm.client_id}`;
                }
                if (clientIdInput && farm.client_id) {
                    clientIdInput.value = String(farm.client_id);
                }
                if (typeof filterFarms === 'function') {
                    filterFarms();
                }
            }

            function filterFarms() {
                const { clientId, groupId } = getSelection();
                Array.from(farmSelect.options).forEach(option => {
                    if (!option.value) return;
                    const matches = clientId
                        ? option.dataset.client === clientId
                        : (groupId ? option.dataset.group === groupId : true);
                    option.hidden = !matches;
                });
                if ((clientId || groupId) && farmSelect.selectedOptions.length && farmSelect.selectedOptions[0].hidden) {
                    farmSelect.value = '';
                }
            }

            function filterLots() {
                if (!lotSelect) return;
                const { clientId, groupId } = getSelection();
                const farmId = farmSelect.value;
                Array.from(lotSelect.options).forEach(option => {
                    if (!option.value) return;
                    const matchesClient = clientId
                        ? option.dataset.client === clientId
                        : (groupId ? option.dataset.group === groupId : true);
                    const matchesFarm = !farmId || option.dataset.farm === farmId;
                    option.hidden = !(matchesClient && matchesFarm);
                });
                if (lotSelect.selectedOptions.length && lotSelect.selectedOptions[0].hidden) {
                    lotSelect.value = '';
                }
            }

            clientSelector?.addEventListener('change', () => {
                applySelectorToHidden();
                filterFarms();
                filterLots();
            });
            farmSelect.addEventListener('change', () => {
                if (lotSelect) {
                    lotSelect.value = '';
                }
                const selected = farmSelect.selectedOptions[0];
                if (selected) {
                    const farmId = farmSelect.value;
                    const farm = farmsGeo.find(f => String(f.id) === String(farmId));
                    if (farm) {
                        setSelectorFromFarm(farm);
                    }
                }
                filterLots();
            });
            function syncFromLotSelection() {
                if (!lotSelect || !lotSelect.value) return;
                const match = findLotById(lotSelect.value);
                if (match) {
                    setSelectorFromFarm(match.farm);
                    if (farmSelect) {
                        farmSelect.value = String(match.farm.id);
                    }
                    const layer = lotLayers.get(match.lot.id);
                    if (layer) {
                        map.fitBounds(layer.getBounds(), { padding: [20, 20] });
                        const center = layer.getBounds().getCenter();
                        setLocation(center.lat, center.lng, 16);
                        highlightLot(match.lot.id);
                    }
                }
                filterLots();
            }

            lotSelect?.addEventListener('change', () => {
                if (!lotSelect.value) return;
                syncFromLotSelection();
            });
            applySelectorToHidden();
            if (!clientSelector?.value && farmSelect?.value) {
                const farm = farmsGeo.find(f => String(f.id) === String(farmSelect.value));
                if (farm) {
                    setSelectorFromFarm(farm);
                }
            }
            filterFarms();
            filterLots();
            syncFromLotSelection();
        });
    </script>
</x-app-layout>
