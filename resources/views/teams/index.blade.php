<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Equipos</h1>
                <p class="text-sm text-gray-500">Equipos registrados en el sistema.</p>
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
                    <a href="{{ route('teams.create') }}"
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
                <span class="text-xs text-gray-500">Total: {{ $teams->count() }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Número</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nombre</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipo de Equipo</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Propiedad</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contratistas</th>
                            @if(auth()->user()->is_admin)
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Organización</th>
                            @endif
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Estado</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white" id="teamsTableBody">
                        @forelse($teams as $team)
                        <tr class="team-row hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $team->id }}</td>

                            <td class="px-4 py-3">
                                <div class="text-sm font-semibold text-gray-900 team-name">{{ $team->name }}</div>
                                <div class="text-xs text-gray-500">Equipo</div>
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-900">{{ $team->tipo_equipo_label }}</td>

                            <td class="px-4 py-3">
                                @if($team->tipo_propiedad == 'propio')
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-700">
                                        Propio
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-purple-100 text-purple-700">
                                        Contratista
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-900">{{ $team->contratistas ?? '-' }}</td>

                            @if(auth()->user()->is_admin)
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $team->organization ? $team->organization->name : 'N/A' }}</td>
                            @endif

                            <td class="px-4 py-3">
                                @if($team->activo)
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-700">
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-red-100 text-red-700">
                                        Inactivo
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                @if(auth()->user()->is_admin || auth()->user()->is_org_admin)
                                <a href="{{ route('teams.edit', $team->id) }}"
                                   class="inline-flex items-center rounded-lg border border-green-600 bg-green-50 px-3 py-1.5 text-sm font-medium text-green-700 hover:bg-green-100">
                                    Editar
                                </a>

                                <form method="POST" action="{{ route('teams.destroy', $team->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('¿Eliminar equipo?')"
                                            class="ml-2 inline-flex items-center rounded-lg bg-red-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-red-700">
                                        Eliminar
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ auth()->user()->is_admin ? '8' : '7' }}" class="px-4 py-10 text-center text-sm text-gray-500">
                                No hay equipos cargados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="teamsTableBody">
                                @forelse($teams as $team)
                                <tr class="team-row">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $team->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 team-name">
                                        {{ $team->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $team->tipo_equipo_label }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($team->tipo_propiedad == 'propio')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Propio
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                Contratista
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $team->contratistas ?? '-' }}
                                    </td>
                                    @if(auth()->user()->is_admin)
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $team->organization ? $team->organization->name : 'N/A' }}
                                    </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($team->activo)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Activo
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            @if(auth()->user()->is_admin || auth()->user()->is_org_admin)
                                            <a href="{{ route('teams.edit', $team->id) }}" class="text-green-600 hover:text-green-900">Editar</a>
                                            <form method="POST" action="{{ route('teams.destroy', $team->id) }}" onsubmit="return confirm('¿Está seguro de que desea eliminar este equipo?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->is_admin ? '8' : '7' }}" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No hay equipos registrados
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.team-row');
            
            rows.forEach(row => {
                const name = row.querySelector('.team-name').textContent.toLowerCase();
                if (name.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</x-app-layout>


