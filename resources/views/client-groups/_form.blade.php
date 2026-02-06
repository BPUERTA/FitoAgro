<div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl p-6 space-y-4">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nombre del grupo</label>
        <input type="text" name="name" id="name" value="{{ old('name', $clientGroup->name ?? '') }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
    </div>

    <div>
        <label for="note" class="block text-sm font-medium text-gray-700">Nota</label>
        <textarea name="note" id="note" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">{{ old('note', $clientGroup->note ?? '') }}</textarea>
    </div>

    <div>
        <div class="flex items-center justify-between">
            <p class="block text-sm font-medium text-gray-700">Clientes y porcentajes</p>
            <button type="button" id="add-member" class="rounded bg-green-600 px-3 py-2 text-xs font-medium text-white hover:bg-green-700">Agregar cliente</button>
        </div>
        <div class="mt-3 space-y-2" id="members-list">
            @php($oldMembers = old('members', isset($clientGroup) ? $clientGroup->members->map(fn($m) => ['client_id' => $m->client_id, 'percentage' => $m->percentage])->toArray() : []))
            @foreach($oldMembers as $index => $member)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-center member-row">
                    <select name="members[{{ $index }}][client_id]" class="w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
                        <option value="">Seleccionar cliente</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ (string) ($member['client_id'] ?? '') === (string) $client->id ? 'selected' : '' }}>
                                {{ $client->number }} - {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="number" step="0.01" min="0" max="100" name="members[{{ $index }}][percentage]" value="{{ $member['percentage'] ?? '' }}" placeholder="Porcentaje" class="w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
                    <button type="button" class="remove-member rounded bg-red-50 px-3 py-2 text-xs font-medium text-red-700 hover:bg-red-100">Quitar</button>
                </div>
            @endforeach
        </div>
        <p class="mt-2 text-xs text-gray-500">La suma de porcentajes debe ser 100%.</p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const list = document.getElementById('members-list');
        const addBtn = document.getElementById('add-member');
        let index = list ? list.querySelectorAll('.member-row').length : 0;

        function buildRow(i) {
            const row = document.createElement('div');
            row.className = 'grid grid-cols-1 md:grid-cols-3 gap-3 items-center member-row';
            row.innerHTML = `
                <select name="members[${i}][client_id]" class="w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
                    <option value="">Seleccionar cliente</option>
                    ${Array.from(document.querySelectorAll('#members-list option'))
                        .filter((opt, idx) => idx > 0)
                        .map(opt => `<option value="${opt.value}">${opt.textContent}</option>`).join('')}
                </select>
                <input type="number" step="0.01" min="0" max="100" name="members[${i}][percentage]" placeholder="Porcentaje" class="w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
                <button type="button" class="remove-member rounded bg-red-50 px-3 py-2 text-xs font-medium text-red-700 hover:bg-red-100">Quitar</button>
            `;
            return row;
        }

        addBtn?.addEventListener('click', () => {
            if (!list) return;
            list.appendChild(buildRow(index));
            index += 1;
        });

        list?.addEventListener('click', (e) => {
            const btn = e.target.closest('.remove-member');
            if (!btn) return;
            btn.closest('.member-row')?.remove();
        });
    });
</script>
