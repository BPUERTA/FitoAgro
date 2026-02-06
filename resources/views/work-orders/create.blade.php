<x-app-layout>
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">New Work Order</h1>
            <p class="text-sm text-gray-500">Create a new work order.</p>
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

        <form action="{{ route('work-orders.store') }}" method="POST" id="workOrderForm">
            @csrf
            @if(!empty($prefillRegistroTecnicoId))
                <input type="hidden" name="registro_tecnico_id" value="{{ $prefillRegistroTecnicoId }}">
            @endif

            <div class="flex gap-2">
                <div class="w-[24%] flex-shrink-0">
                    <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">General</h2>
                        </div>

                        <div class="p-6 space-y-6">
                            <div>
                                <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Client <span class="text-red-500">*</span>
                                </label>
                                <select name="client_id" id="client_id" required
                                        class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                    <option value="">Select client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('client_id', $prefillClientId ?? null) == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">
                                    Priority
                                </label>
                                <select name="priority" id="priority"
                                        class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                    @foreach($priorities as $key => $label)
                                        <option value="{{ $key }}" {{ old('priority', 'medium') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="scheduled_start_at" class="block text-sm font-medium text-gray-700 mb-1">
                                    Scheduled start
                                </label>
                                <input type="datetime-local" name="scheduled_start_at" id="scheduled_start_at"
                                       value="{{ old('scheduled_start_at') }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            </div>

                            <div>
                                <label for="scheduled_end_at" class="block text-sm font-medium text-gray-700 mb-1">
                                    Scheduled end
                                </label>
                                <input type="datetime-local" name="scheduled_end_at" id="scheduled_end_at"
                                       value="{{ old('scheduled_end_at') }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                    Status
                                </label>
                                <select name="status" id="status"
                                        class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                    <option value="pendiente" {{ old('status', 'pendiente') == 'pendiente' ? 'selected' : '' }}>Pending</option>
                                    <option value="abierto" {{ old('status') == 'abierto' ? 'selected' : '' }}>Open</option>
                                    <option value="cerrado" {{ old('status') == 'cerrado' ? 'selected' : '' }}>Closed</option>
                                    <option value="cancelado" {{ old('status') == 'cancelado' ? 'selected' : '' }}>Canceled</option>
                                </select>
                            </div>

                            <div id="canceledReasonWrapper" class="hidden">
                                <label for="canceled_reason" class="block text-sm font-medium text-gray-700 mb-1">
                                    Canceled reason
                                </label>
                                <textarea name="canceled_reason" id="canceled_reason" rows="2"
                                          class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">{{ old('canceled_reason') }}</textarea>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                    Description
                                </label>
                                <textarea name="description" id="description" rows="3"
                                          class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-1">
                    <div id="blocksCard" class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                            <h2 class="text-lg font-medium text-gray-900">Blocks</h2>
                            <button type="button" id="addBlockBtn" class="inline-flex items-center rounded-lg bg-green-600 px-3 py-1 text-sm font-medium text-white hover:bg-green-700">
                                Add block
                            </button>
                        </div>

                        <div id="blocksContainer" class="p-6 space-y-4"></div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('work-orders.index') }}"
                   class="inline-flex items-center rounded-lg bg-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-400">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center rounded-lg bg-purple-600 px-4 py-2 text-sm font-medium text-white hover:bg-purple-700">
                    Create
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            const canceledWrapper = document.getElementById('canceledReasonWrapper');
            const canceledReason = document.getElementById('canceled_reason');

            function toggleCanceledReason() {
                const show = statusSelect.value === 'cancelado';
                canceledWrapper.classList.toggle('hidden', !show);
                canceledReason.required = show;
            }

            statusSelect?.addEventListener('change', toggleCanceledReason);
            toggleCanceledReason();

            const blocksContainer = document.getElementById('blocksContainer');
            const addBlockBtn = document.getElementById('addBlockBtn');
            const clientSelectEl = document.getElementById('client_id');
            const workOrderForm = document.getElementById('workOrderForm');

            let availableFarms = [];
            let availableProducts = @json($products ?? []);

            async function ensureProductsLoaded() {
                if (Array.isArray(availableProducts) && availableProducts.length > 0) return;
                try {
                    const res = await fetch('/products/search', { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) return;
                    const data = await res.json();
                    if (data && data.products) availableProducts = data.products;
                } catch (e) {
                    console.error('Unable to load products:', e);
                }
            }

            ensureProductsLoaded();

            async function loadFarmsForClient(clientId) {
                if (!clientId) {
                    availableFarms = [];
                    updateAllFarmSelects();
                    return;
                }

                try {
                    const res = await fetch(`/clients/${clientId}/farms`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (!res.ok) { availableFarms = []; updateAllFarmSelects(); return; }
                    availableFarms = await res.json();
                    updateAllFarmSelects();
                } catch (e) {
                    console.error('Error loading farms:', e);
                    availableFarms = [];
                    updateAllFarmSelects();
                }
            }

            clientSelectEl?.addEventListener('change', function() {
                loadFarmsForClient(this.value);
            });

            function letterFromIndex(i) {
                return String.fromCharCode('A'.charCodeAt(0) + i);
            }

            function getNextBlockLetter() {
                const used = new Set(
                    Array.from(blocksContainer.querySelectorAll('input[data-block-letter]'))
                        .map(i => i.value)
                        .filter(Boolean)
                );
                let code = 'A'.charCodeAt(0);
                while (used.has(String.fromCharCode(code))) {
                    code++;
                }
                return String.fromCharCode(code);
            }

            function updateBlockInputs() {
                const blocks = blocksContainer.querySelectorAll('.block-item');
                blocks.forEach((blockEl, idx) => {
                    const hiddenLetter = blockEl.querySelector('input[data-block-letter]');
                    let letter = hiddenLetter?.value;
                    if (!letter) {
                        letter = letterFromIndex(idx);
                        if (hiddenLetter) hiddenLetter.value = letter;
                    }
                    blockEl.querySelectorAll('.block-letter').forEach(el => el.textContent = letter);
                });
            }

            function blockTotal(blockEl) {
                let total = 0;
                blockEl.querySelectorAll('input[data-has]').forEach(a => {
                    const v = parseFloat(a.value);
                    if (!isNaN(v)) total += v;
                });
                return total;
            }

            function updateBlockTotals() {
                blocksContainer.querySelectorAll('.block-item').forEach(blockEl => {
                    const total = blockTotal(blockEl);
                    const display = blockEl.querySelector('.block-total');
                    if (display) display.textContent = total.toFixed(2);

                    blockEl.querySelectorAll('input[data-total-has]').forEach(input => {
                        input.value = total.toFixed(2);
                    });

                    blockEl.querySelectorAll('.product-row').forEach(row => {
                        const dosis = row.querySelector('input[data-dosis]');
                        const totalLine = row.querySelector('input[data-total-linea]');
                        const totalHas = total;
                        if (dosis && totalLine && totalHas > 0 && dosis.value !== '') {
                            totalLine.value = (parseFloat(dosis.value) * totalHas).toFixed(2);
                        }
                    });
                });
            }

            function populateFarmSelectOptions(selectEl) {
                selectEl.innerHTML = '<option value="">Select farm</option>';
                availableFarms.forEach(f => {
                    const opt = document.createElement('option');
                    opt.value = f.id;
                    opt.textContent = `${f.name} (${f.has} ha)`;
                    opt.dataset.name = f.name || '';
                    selectEl.appendChild(opt);
                });
            }

            function updateAllFarmSelects() {
                document.querySelectorAll('select[data-farm-select]').forEach(s => populateFarmSelectOptions(s));
            }

            function populateProductSelectOptions(selectEl) {
                selectEl.innerHTML = '<option value="">Select product</option>';
                availableProducts.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.id;
                    opt.textContent = `${p.descripcion}`;
                    opt.dataset.um_dosis = p.um_dosis || '';
                    opt.dataset.um_total = p.um_total || '';
                    selectEl.appendChild(opt);
                });
            }

            function createFarmRow(prefill = {}) {
                const row = document.createElement('div');
                row.className = 'farm-row flex items-center gap-2 flex-wrap';
                if (prefill.id) row.dataset.lineId = prefill.id;

                const select = document.createElement('select');
                select.className = 'rounded-lg border-gray-300 p-2 text-sm';
                select.setAttribute('data-farm-select', '');

                const nameInput = document.createElement('input');
                nameInput.type = 'text';
                nameInput.placeholder = 'Farm name';
                nameInput.className = 'w-48 rounded-lg border-gray-300 p-2 text-sm';
                nameInput.setAttribute('data-name', '');

                const hasInput = document.createElement('input');
                hasInput.type = 'number';
                hasInput.step = '0.01';
                    hasInput.min = '0.01';
                hasInput.placeholder = 'Ha';
                hasInput.className = 'w-24 rounded-lg border-gray-300 p-2 text-sm';
                hasInput.setAttribute('data-has', '');

                const distanceInput = document.createElement('input');
                distanceInput.type = 'number';
                distanceInput.step = '0.01';
                distanceInput.min = '0';
                distanceInput.placeholder = 'Distance';
                distanceInput.className = 'w-28 rounded-lg border-gray-300 p-2 text-sm';
                distanceInput.setAttribute('data-distance', '');

                const dateInput = document.createElement('input');
                dateInput.type = 'date';
                dateInput.className = 'rounded-lg border-gray-300 p-2 text-sm';
                dateInput.setAttribute('data-date', '');

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'inline-flex items-center rounded-lg bg-red-50 px-2 py-1 text-sm text-red-700 hover:bg-red-100';
                removeBtn.textContent = 'Remove';
                removeBtn.addEventListener('click', function() {
                    row.remove();
                    updateBlockTotals();
                });

                select.addEventListener('change', function() {
                    const opt = this.options[this.selectedIndex];
                    if (opt && opt.dataset.name && nameInput.value.trim() === '') {
                        nameInput.value = opt.dataset.name;
                    }
                });

                hasInput.addEventListener('input', updateBlockTotals);

                populateFarmSelectOptions(select);

                if (prefill.exploitation_id) select.value = prefill.exploitation_id;
                if (prefill.exploitation_name) nameInput.value = prefill.exploitation_name;
                if (prefill.has) hasInput.value = prefill.has;
                if (prefill.distancia_poblado) distanceInput.value = prefill.distancia_poblado;
                if (prefill.fecha_aplicacion) dateInput.value = prefill.fecha_aplicacion;

                row.appendChild(select);
                row.appendChild(nameInput);
                row.appendChild(hasInput);
                row.appendChild(distanceInput);
                row.appendChild(dateInput);
                row.appendChild(removeBtn);

                return row;
            }

            function createProductRow(prefill = {}) {
                const row = document.createElement('div');
                row.className = 'product-row flex items-center gap-2 flex-wrap';
                if (prefill.id) row.dataset.lineId = prefill.id;

                const select = document.createElement('select');
                select.className = 'rounded-lg border-gray-300 p-2 text-sm';
                select.setAttribute('data-product-select', '');

                const dosisInput = document.createElement('input');
                dosisInput.type = 'number';
                dosisInput.step = '0.01';
                    dosisInput.min = '0.01';
                dosisInput.placeholder = 'Dose';
                dosisInput.className = 'w-24 rounded-lg border-gray-300 p-2 text-sm';
                dosisInput.setAttribute('data-dosis', '');

                const umInput = document.createElement('input');
                umInput.type = 'text';
                umInput.placeholder = 'UM dose';
                umInput.className = 'w-24 rounded-lg border-gray-300 p-2 text-sm';
                umInput.setAttribute('data-um', '');
                umInput.readOnly = true;

                const totalHasInput = document.createElement('input');
                totalHasInput.type = 'number';
                totalHasInput.step = '0.01';
                    totalHasInput.min = '0.01';
                totalHasInput.placeholder = 'Block ha';
                totalHasInput.className = 'w-24 rounded-lg border-gray-300 p-2 text-sm';
                totalHasInput.setAttribute('data-total-has', '');
                totalHasInput.readOnly = true;

                const totalLineInput = document.createElement('input');
                totalLineInput.type = 'number';
                totalLineInput.step = '0.01';
                    totalLineInput.min = '0.01';
                totalLineInput.placeholder = 'Line total';
                totalLineInput.className = 'w-28 rounded-lg border-gray-300 p-2 text-sm';
                totalLineInput.setAttribute('data-total-linea', '');

                const umTotalInput = document.createElement('input');
                umTotalInput.type = 'text';
                umTotalInput.placeholder = 'UM total';
                umTotalInput.className = 'w-24 rounded-lg border-gray-300 p-2 text-sm';
                umTotalInput.setAttribute('data-um-total', '');
                umTotalInput.readOnly = true;

                const priceInput = document.createElement('input');
                priceInput.type = 'number';
                priceInput.step = '0.01';
                priceInput.min = '0';
                priceInput.placeholder = 'Unit price';
                priceInput.className = 'w-24 rounded-lg border-gray-300 p-2 text-sm';
                priceInput.setAttribute('data-price', '');

                const noteInput = document.createElement('input');
                noteInput.type = 'text';
                noteInput.placeholder = 'Note';
                noteInput.className = 'w-40 rounded-lg border-gray-300 p-2 text-sm';
                noteInput.setAttribute('data-note', '');

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'inline-flex items-center rounded-lg bg-red-50 px-2 py-1 text-sm text-red-700 hover:bg-red-100';
                removeBtn.textContent = 'Remove';
                removeBtn.addEventListener('click', function(){ row.remove(); });

                function recomputeFromDosis() {
                    const totalHas = parseFloat(totalHasInput.value) || 0;
                    const dosis = parseFloat(dosisInput.value);
                    if (totalHas > 0 && !isNaN(dosis)) {
                        totalLineInput.value = (dosis * totalHas).toFixed(2);
                    }
                }

                function recomputeFromTotal() {
                    const totalHas = parseFloat(totalHasInput.value) || 0;
                    const totalLine = parseFloat(totalLineInput.value);
                    if (totalHas > 0 && !isNaN(totalLine)) {
                        dosisInput.value = (totalLine / totalHas).toFixed(4);
                    }
                }

                select.addEventListener('change', function(){
                    const opt = this.options[this.selectedIndex];
                    if (opt) {
                        umInput.value = opt.dataset.um_dosis || '';
                        umTotalInput.value = opt.dataset.um_total || '';
                    }
                });
                dosisInput.addEventListener('input', recomputeFromDosis);
                totalLineInput.addEventListener('input', recomputeFromTotal);

                populateProductSelectOptions(select);

                if (prefill.product_id) select.value = prefill.product_id;
                if (prefill.dosis) dosisInput.value = prefill.dosis;
                if (prefill.um_dosis) umInput.value = prefill.um_dosis;
                if (prefill.total_linea_producto) totalLineInput.value = prefill.total_linea_producto;
                if (prefill.um_total) umTotalInput.value = prefill.um_total;
                if (prefill.total_has_bloque) totalHasInput.value = prefill.total_has_bloque;
                if (prefill.precio_unitario) priceInput.value = prefill.precio_unitario;
                if (prefill.nota) noteInput.value = prefill.nota;

                row.appendChild(select);
                row.appendChild(dosisInput);
                row.appendChild(umInput);
                row.appendChild(totalHasInput);
                row.appendChild(totalLineInput);
                row.appendChild(umTotalInput);
                row.appendChild(priceInput);
                row.appendChild(noteInput);
                row.appendChild(removeBtn);

                return row;
            }

            function createBlockElement(letter) {
                const wrapper = document.createElement('div');
                wrapper.className = 'block-item border border-gray-200 rounded-lg p-4 bg-gray-50 flex items-start justify-between gap-4';
                wrapper.innerHTML = `
                    <div class="w-full">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-lg font-bold"> <span class="block-letter">${letter}</span> </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">Block <span class="block-letter">${letter}</span></div>
                                    <div class="text-xs text-gray-600">Total ha: <span class="font-medium block-total">0</span></div>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <button type="button" class="remove-block inline-flex items-center rounded-lg bg-red-100 px-2 py-1 text-sm text-red-700 hover:bg-red-200">Remove</button>
                            </div>
                        </div>

                        <div class="mt-3 bg-white p-3 border border-gray-100 rounded-md">
                            <input type="hidden" data-block-letter value="${letter}" />
                            <div class="farm-rows space-y-2"></div>
                            <div class="mt-2">
                                <button type="button" class="add-farm inline-flex items-center rounded-lg bg-blue-600 px-3 py-1 text-sm font-medium text-white hover:bg-blue-700">Add farm</button>
                            </div>
                        </div>

                        <div class="mt-4 bg-white p-3 border border-gray-100 rounded-md">
                            <div class="text-xs text-gray-700 mb-2 font-medium">Products</div>
                            <div class="product-rows space-y-2"></div>
                            <div class="mt-2">
                                <button type="button" class="add-product inline-flex items-center rounded-lg bg-indigo-600 px-3 py-1 text-sm font-medium text-white hover:bg-indigo-700">Add product</button>
                            </div>
                        </div>
                    </div>
                `;

                wrapper.querySelector('.remove-block').addEventListener('click', function() {
                    const all = blocksContainer.querySelectorAll('.block-item');
                    if (all.length <= 1) return;
                    wrapper.remove();
                    updateBlockInputs();
                    updateBlockTotals();
                });

                const farmRowsContainer = wrapper.querySelector('.farm-rows');
                const addFarmBtn = wrapper.querySelector('.add-farm');
                const productRowsContainer = wrapper.querySelector('.product-rows');
                const addProductBtn = wrapper.querySelector('.add-product');

                addFarmBtn.addEventListener('click', function() {
                    const row = createFarmRow();
                    farmRowsContainer.appendChild(row);
                    updateBlockTotals();
                });

                addProductBtn.addEventListener('click', function() {
                    const row = createProductRow();
                    productRowsContainer.appendChild(row);
                    updateBlockTotals();
                });

                return wrapper;
            }

            function addBlock(letter = null) {
                const blockLetter = letter || getNextBlockLetter();
                const el = createBlockElement(blockLetter);
                blocksContainer.appendChild(el);
                updateBlockInputs();
                updateBlockTotals();
                return el;
            }

            if (blocksContainer && blocksContainer.children.length === 0) {
                addBlock();
            }

            const prefillClientId = @json($prefillClientId ?? null);
            const prefillFarm = @json($prefillFarm ?? null);

            async function applyPrefill() {
                if (!prefillClientId && !prefillFarm) return;

                if (prefillClientId) {
                    clientSelectEl.value = String(prefillClientId);
                    await loadFarmsForClient(prefillClientId);
                } else if (clientSelectEl?.value) {
                    await loadFarmsForClient(clientSelectEl.value);
                }

                if (prefillFarm) {
                    const block = blocksContainer.querySelector('.block-item') || addBlock();
                    const farmRowsContainer = block.querySelector('.farm-rows');
                    const row = createFarmRow({
                        exploitation_id: prefillFarm.exploitation_id,
                        exploitation_name: prefillFarm.exploitation_name,
                        has: prefillFarm.has,
                        distancia_poblado: prefillFarm.distancia_poblado,
                    });
                    farmRowsContainer.appendChild(row);
                    updateBlockTotals();
                }
            }

            function buildFlatInputs() {
                const oldFarms = document.querySelectorAll('input[name^="farms"]');
                oldFarms.forEach(n => n.remove());
                const oldProducts = document.querySelectorAll('input[name^="products"]');
                oldProducts.forEach(n => n.remove());

                const blocks = blocksContainer.querySelectorAll('.block-item');
                let farmIdx = 0;
                let productIdx = 0;

                blocks.forEach(blockEl => {
                    const letter = blockEl.querySelector('input[data-block-letter]')?.value || blockEl.querySelector('.block-letter')?.textContent || 'A';

                    blockEl.querySelectorAll('.farm-row').forEach(row => {
                        const farmId = row.querySelector('select[data-farm-select]')?.value || '';
                        const name = row.querySelector('input[data-name]')?.value || '';
                        const has = row.querySelector('input[data-has]')?.value || '';
                        const distance = row.querySelector('input[data-distance]')?.value || '';
                        const date = row.querySelector('input[data-date]')?.value || '';
                        const hasValue = parseFloat(has);

                        if (!name || Number.isNaN(hasValue) || hasValue <= 0) {
                            return;
                        }

                        const inputs = [
                            ['id', row.dataset.lineId || ''],
                            ['bloque', letter],
                            ['exploitation_id', farmId],
                            ['exploitation_name', name],
                            ['has', has],
                            ['distancia_poblado', distance],
                            ['fecha_aplicacion', date]
                        ];

                        inputs.forEach(([key, value]) => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = `farms[${farmIdx}][${key}]`;
                            input.value = value;
                            workOrderForm.appendChild(input);
                        });

                        farmIdx++;
                    });

                    blockEl.querySelectorAll('.product-row').forEach(row => {
                        const select = row.querySelector('select[data-product-select]');
                        const opt = select?.options[select.selectedIndex];
                        const productId = select?.value || '';
                        const productName = opt?.textContent || '';
                        const dosis = row.querySelector('input[data-dosis]')?.value || '';
                        const umDosis = row.querySelector('input[data-um]')?.value || '';
                        const totalHas = row.querySelector('input[data-total-has]')?.value || '';
                        const totalLinea = row.querySelector('input[data-total-linea]')?.value || '';
                        const umTotal = row.querySelector('input[data-um-total]')?.value || '';
                        const price = row.querySelector('input[data-price]')?.value || '';
                        const note = row.querySelector('input[data-note]')?.value || '';
                        const totalHasValue = parseFloat(totalHas);

                        if (!productId || Number.isNaN(totalHasValue) || totalHasValue <= 0) {
                            return;
                        }

                        const inputs = [
                            ['id', row.dataset.lineId || ''],
                            ['bloque', letter],
                            ['total_has_bloque', totalHas],
                            ['product_id', productId],
                            ['product_name', productName],
                            ['dosis', dosis],
                            ['um_dosis', umDosis],
                            ['total_linea_producto', totalLinea],
                            ['um_total', umTotal],
                            ['precio_unitario', price],
                            ['nota', note]
                        ];

                        inputs.forEach(([key, value]) => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = `products[${productIdx}][${key}]`;
                            input.value = value;
                            workOrderForm.appendChild(input);
                        });

                        productIdx++;
                    });
                });
            }

            workOrderForm?.addEventListener('submit', function() {
                updateBlockInputs();
                updateBlockTotals();
                buildFlatInputs();
            });

            loadFarmsForClient(clientSelectEl?.value);
            applyPrefill();
        });
    </script>
    @endpush
</x-app-layout>
