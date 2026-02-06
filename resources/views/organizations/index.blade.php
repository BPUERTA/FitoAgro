{{-- resources/views/organizations/index.blade.php --}}

<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Organizaciones</h1>
                <p class="text-sm text-gray-500">Empresas asesoras registradas en el sistema.</p>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-full max-w-xs">
                    <input
                        type="text"
                        placeholder="Buscar..."
                        class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"
                    />
                </div>

                @if(auth()->check() && auth()->user()->is_admin == 1)
                    <a href="{{ route('organizations.create') }}"
                       class="inline-flex items-center rounded-lg bg-green-600 px-3 py-2 text-sm font-medium text-white hover:bg-green-700">
                        Nuevo
                    </a>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
                <span class="text-sm font-medium text-gray-700">Listado</span>
                <span class="text-xs text-gray-500">Total: {{ $organizations->count() }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nombre</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Plan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Estado</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($organizations as $org)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $org->id }}</td>

                                <td class="px-4 py-3">
                                    <div class="text-sm font-semibold text-gray-900">{{ $org->name }}</div>
                                    <div class="text-xs text-gray-500">Empresa asesora</div>
                                </td>

                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $org->plan?->name ?? $org->subscription_plan ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    @php $active = $org->status === 'active'; @endphp
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                        {{ $active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <a href="{{ route('organizations.show', $org) }}"
                                       class="inline-flex items-center rounded-lg bg-green-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-green-700">
                                        Ver
                                    </a>

                                    @if(auth()->user()?->is_admin)
                                        <a href="{{ route('organizations.edit', $org) }}"
                                           class="ml-2 inline-flex items-center rounded-lg border border-green-600 bg-green-50 px-3 py-1.5 text-sm font-medium text-green-700 hover:bg-green-100">
                                            Editar
                                        </a>

                                        <form action="{{ route('organizations.destroy', $org) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('¿Eliminar organización?')"
                                                    class="ml-2 inline-flex items-center rounded-lg bg-red-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-red-700">
                                                Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-10 text-center text-sm text-gray-500">
                                    No hay organizaciones cargadas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>

