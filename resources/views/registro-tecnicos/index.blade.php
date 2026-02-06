<x-app-layout>
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Registro Técnico</h1>
                <p class="text-sm text-gray-500">Gestión de registros técnicos por organización.</p>
            </div>
            <a href="{{ route('registro-tecnicos.create') }}"
               class="inline-flex items-center rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                Nuevo Registro Técnico
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif
        @if(!empty($tableMissing))
            <div class="mb-4 rounded-lg border border-yellow-200 bg-yellow-50 p-4 text-sm text-yellow-800">
                El módulo de Registro Técnico aún no está migrado. Ejecuta las migraciones para habilitarlo.
            </div>
        @endif

        <form method="GET" class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Estado</label>
                <select name="status" class="w-full rounded-lg border-gray-300 text-sm">
                    <option value="">Todos</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Cliente</label>
                <select name="client_id" class="w-full rounded-lg border-gray-300 text-sm">
                    <option value="">Todos</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ (string) request('client_id') === (string) $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white">Filtrar</button>
            </div>
        </form>

        <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl overflow-hidden">
            <div class="overflow-x-auto" style="-webkit-overflow-scrolling: touch;">
                <table class="min-w-[900px] w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Código</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Estado</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Cliente</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Explotación</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Lote</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Objetivos</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Fecha</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($registroTecnicos as $registro)
                        @php
                            $statusClass = match($registro->status) {
                                'abierto' => 'bg-green-100 text-green-800 ring-green-200',
                                'en_proceso' => 'bg-amber-100 text-amber-800 ring-amber-200',
                                'cerrado' => 'bg-blue-100 text-blue-800 ring-blue-200',
                                'cancelado' => 'bg-red-100 text-red-800 ring-red-200',
                                default => 'bg-gray-100 text-gray-700 ring-gray-200',
                            };
                        @endphp
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $registro->code }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusClass }}">
                                    {{ $statuses[$registro->status] ?? ucfirst($registro->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $registro->client?->name ?? 'Pendiente' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $registro->farm?->name ?? 'Pendiente' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $registro->lot?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $registro->objectives ? implode(', ', array_map('ucfirst', $registro->objectives)) : '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $registro->created_at?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('registro-tecnicos.show', $registro) }}" class="text-sm text-green-600 hover:text-green-800">Ver</a>
                                @can('update', $registro)
                                    <span class="text-gray-300 mx-1">|</span>
                                    <a href="{{ route('registro-tecnicos.edit', $registro) }}" class="text-sm text-gray-600 hover:text-gray-800">Editar</a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500">No hay registros técnicos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $registroTecnicos->links() }}
        </div>
    </div>
</x-app-layout>
