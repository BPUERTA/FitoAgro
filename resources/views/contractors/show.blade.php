<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalle del Contratista') }}
            </h2>
            <a href="{{ route('contractors.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <tbody>
                                <tr class="border-b">
                                    <td class="py-3 px-4 font-semibold text-gray-700 bg-gray-50">ID:</td>
                                    <td class="py-3 px-4 text-gray-900">{{ $contractor->numero }}</td>
                                    <td class="py-3 px-4 font-semibold text-gray-700 bg-gray-50">Nombre / Razón Social:</td>
                                    <td class="py-3 px-4 text-gray-900">{{ $contractor->nombre }}</td>
                                    <td class="py-3 px-4 font-semibold text-gray-700 bg-gray-50">CUIT:</td>
                                    <td class="py-3 px-4 text-gray-900">{{ $contractor->cuit }}</td>
                                    <td class="py-3 px-4 font-semibold text-gray-700 bg-gray-50">Estado:</td>
                                    <td class="py-3 px-4">
                                        @if($contractor->activo)
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800">
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-800">
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    @if(auth()->user()->is_admin || auth()->user()->is_org_admin)
                        <div class="mt-6 flex gap-3">
                            <a href="{{ route('contractors.edit', $contractor) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Editar
                            </a>
                            <form action="{{ route('contractors.destroy', $contractor) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Estás seguro de eliminar este contratista?')" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Equipos del contratista -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Equipos Asignados</h3>
                        @if(auth()->user()->is_admin || auth()->user()->is_org_admin)
                            <a href="{{ route('teams.create', ['contractor_id' => $contractor->id]) }}" class="inline-flex items-center rounded-lg bg-green-600 px-3 py-2 text-sm font-medium text-white hover:bg-green-700">
                                Nuevo Equipo
                            </a>
                        @endif
                    </div>
                    
                    @if($contractor->teams->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">ID</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nombre</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tipo</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Estado</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach($contractor->teams as $team)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm text-gray-700">{{ $team->id }}</td>
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $team->name }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">{{ $team->tipo_equipo_label }}</td>
                                            <td class="px-4 py-3 text-sm">
                                                @if($team->activo)
                                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">
                                                        Activo
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">
                                                        Inactivo
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <a href="{{ route('teams.show', $team) }}" class="text-blue-600 hover:text-blue-800">
                                                    Ver
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>No hay equipos asignados a este contratista.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
