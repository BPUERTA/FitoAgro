<x-app-layout>
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Grupos de Clientes</h1>
                <p class="text-sm text-gray-500">Clientes que comparten explotaciones con porcentajes.</p>
            </div>
            <a href="{{ route('client-groups.create') }}"
               class="inline-flex items-center rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                Nuevo Grupo
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Nombre</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Clientes</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Nota</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($groups as $group)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $group->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                @foreach($group->members as $member)
                                    <div>{{ $member->client?->name ?? '—' }} ({{ number_format((float) $member->percentage, 2) }}%)</div>
                                @endforeach
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $group->note ?? '—' }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('client-groups.edit', $group) }}" class="text-sm text-gray-600 hover:text-gray-800">Editar</a>
                                <span class="text-gray-300 mx-1">|</span>
                                <form action="{{ route('client-groups.destroy', $group) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar grupo?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-800">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">No hay grupos cargados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
