<x-app-layout>
<div class="max-w-2xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Detalle de Profesional</h1>
    <div class="bg-white p-6 rounded shadow space-y-4">
        <div>
            <span class="font-semibold text-gray-700">Nombre Completo:</span>
            <span class="text-gray-900">{{ $professional->nombre_completo }}</span>
        </div>
        <div>
            <span class="font-semibold text-gray-700">Matrícula:</span>
            <span class="text-gray-900">{{ $professional->matricula }}</span>
        </div>
        <div>
            <span class="font-semibold text-gray-700">N° Registro:</span>
            <span class="text-gray-900">{{ $professional->numero_registro ?? '-' }}</span>
        </div>
        <div>
            <span class="font-semibold text-gray-700">Localidad:</span>
            <span class="text-gray-900">{{ $professional->localidad }}</span>
        </div>
        <div>
            <span class="font-semibold text-gray-700">Provincia:</span>
            <span class="text-gray-900">{{ $professional->provincia }}</span>
        </div>
        <div>
            <span class="font-semibold text-gray-700">Estado:</span>
            <span class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $professional->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $professional->activo ? 'Activo' : 'Inactivo' }}
            </span>
        </div>
        <div class="flex space-x-2 mt-6">
            @if(auth()->user()->is_admin || auth()->user()->is_org_admin)
                <a href="{{ route('professionals.edit', $professional) }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Editar</a>
            @endif
            <a href="{{ route('professionals.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Volver</a>
        </div>
    </div>
</div>
</x-app-layout>

