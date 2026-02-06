<x-app-layout>
<div class="max-w-lg mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Detalle de Usuario</h1>
    <div class="bg-white p-6 rounded shadow space-y-4">
        <div>
            <span class="font-semibold text-gray-700">ID:</span> {{ $user->id }}
        </div>
        <div>
            <span class="font-semibold text-gray-700">Usuario:</span> 
            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-green-100 text-green-800">
                {{ $user->nickname ?? 'N/A' }}
            </span>
        </div>
        <div>
            <span class="font-semibold text-gray-700">Nombre:</span> {{ $user->nombre ?? 'N/A' }}
        </div>
        <div>
            <span class="font-semibold text-gray-700">Apellido:</span> {{ $user->apellido ?? 'N/A' }}
        </div>
        <div>
            <span class="font-semibold text-gray-700">Email:</span> {{ $user->email }}
        </div>
        <div>
            <span class="font-semibold text-gray-700">Organización:</span> 
            @if($user->organization)
                {{ $user->organization->name }}
                <span class="text-gray-500 text-sm">(ID: {{ $user->organization_id }})</span>
            @else
                <span class="text-gray-400">Sin organización</span>
            @endif
        </div>
        <div>
            <span class="font-semibold text-gray-700">Admin de organización:</span> {{ $user->is_org_admin ? 'Sí' : 'No' }}
        </div>
        <a href="{{ route('users.index') }}" class="inline-block mt-4 px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Volver</a>
    </div>
</div>
</x-app-layout>

