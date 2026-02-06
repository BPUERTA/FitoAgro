<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Usuarios</h1>
                <p class="text-sm text-gray-500">Usuarios registrados en el sistema.</p>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-full max-w-xs">
                    <input
                        type="text"
                        id="searchInput"
                        placeholder="Buscar..."
                        class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"
                    />
                </div>

                @if(auth()->user()->is_admin || auth()->user()->is_org_admin)
                    <a href="{{ route('users.create') }}"
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

        @if(session('error'))
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
                <span class="text-sm font-medium text-gray-700">Listado</span>
                <span class="text-xs text-gray-500">Total: {{ $users->count() }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Número</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Usuario</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nombre</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Organización</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Permisos</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $user->id }}</td>

                                <td class="px-4 py-3">
                                    <div class="text-sm font-semibold text-gray-900">{{ $user->nickname ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">Usuario</div>
                                </td>

                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $user->nombre ?? 'N/A' }} {{ $user->apellido ?? '' }}
                                </td>

                                <td class="px-4 py-3 text-sm text-gray-900">{{ $user->email }}</td>

                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $user->organization->name ?? 'Sin organización' }}
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-1">
                                        @if($user->is_admin)
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-purple-100 text-purple-800">
                                                Super Admin
                                            </span>
                                        @endif
                                        @if($user->is_org_admin)
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-800">
                                                Admin
                                            </span>
                                        @endif
                                        @if(!$user->is_admin && !$user->is_org_admin)
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-800">
                                                Usuario
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <a href="{{ route('users.show', $user) }}"
                                       class="inline-flex items-center rounded-lg bg-green-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-green-700">
                                        Ver
                                    </a>

                                    @if(auth()->user()->is_admin || (auth()->user()->is_org_admin && $user->organization_id == auth()->user()->organization_id))
                                        <a href="{{ route('users.edit', $user) }}"
                                           class="ml-2 inline-flex items-center rounded-lg border border-green-600 bg-green-50 px-3 py-1.5 text-sm font-medium text-green-700 hover:bg-green-100">
                                            Editar
                                        </a>

                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('¿Eliminar usuario?')"
                                                    class="ml-2 inline-flex items-center rounded-lg bg-red-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-red-700">
                                                Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-sm text-gray-500">
                                    No hay usuarios cargados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('tbody tr');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
</x-app-layout>

