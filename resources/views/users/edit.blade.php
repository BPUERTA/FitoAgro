<x-app-layout>
    <div class="max-w-lg mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">Editar Usuario</h1>

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('users.update', $user) }}" class="bg-white p-6 rounded shadow space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label for="nickname" class="block text-sm font-medium text-gray-700">Usuario</label>
            <input 
                type="text" 
                id="nickname" 
                name="nickname" 
                value="{{ old('nickname', $user->nickname) }}" 
                @if(!auth()->user()->is_admin) readonly @endif
                class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 {{ !auth()->user()->is_admin ? 'bg-gray-100' : '' }}"
            >
            @if(!auth()->user()->is_admin)
                <p class="mt-1 text-xs text-gray-500">Solo puede ser modificado por el administrador del sistema</p>
            @endif
            @error('nickname')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $user->nombre) }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
            @error('nombre')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="apellido" class="block text-sm font-medium text-gray-700">Apellido</label>
            <input type="text" id="apellido" name="apellido" value="{{ old('apellido', $user->apellido) }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
            @error('apellido')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        @if(auth()->user()->is_admin)
        <div>
            <label for="organization_id" class="block text-sm font-medium text-gray-700">Organización</label>
            <select id="organization_id" name="organization_id" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
                <option value="">Sin organización</option>
                @foreach($organizations as $organization)
                    <option value="{{ $organization->id }}" {{ old('organization_id', $user->organization_id) == $organization->id ? 'selected' : '' }}>
                        {{ $organization->name }}
                    </option>
                @endforeach
            </select>
            @error('organization_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        @endif
        
        @if(auth()->user()->is_admin || auth()->user()->is_org_admin)
        <div class="border-t pt-4">
            <h3 class="text-lg font-medium text-gray-900 mb-3">Permisos</h3>
            
            @if(auth()->user()->is_admin || auth()->user()->is_org_admin)
            <div class="flex items-center mb-3">
                <input 
                    type="checkbox" 
                    id="is_org_admin" 
                    name="is_org_admin" 
                    value="1" 
                    {{ old('is_org_admin', $user->is_org_admin) ? 'checked' : '' }}
                    @if(isset($isOnlyOrgAdmin) && $isOnlyOrgAdmin) disabled @endif
                    class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500 {{ isset($isOnlyOrgAdmin) && $isOnlyOrgAdmin ? 'opacity-50 cursor-not-allowed' : '' }}"
                >
                <label for="is_org_admin" class="ml-2 text-sm text-gray-700">
                    Administrador de Organización
                    <span class="block text-xs text-gray-500">Puede gestionar usuarios, clientes, explotaciones, profesionales y equipos de su organización</span>
                    @if(isset($isOnlyOrgAdmin) && $isOnlyOrgAdmin)
                    <span class="block text-xs text-amber-600 font-medium mt-1">No se puede desactivar porque es el único administrador de la organización</span>
                    @endif
                </label>
            </div>
            @endif
            
            @if(auth()->user()->is_admin)
            <div class="flex items-center">
                <input 
                    type="checkbox" 
                    id="is_admin" 
                    name="is_admin" 
                    value="1" 
                    {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500"
                >
                <label for="is_admin" class="ml-2 text-sm text-gray-700">
                    Super Administrador
                    <span class="block text-xs text-gray-500">Acceso total al sistema, puede gestionar todas las organizaciones</span>
                </label>
            </div>
            @endif
        </div>
    @endif
        
        <div class="flex space-x-2">
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Guardar</button>
            <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Cancelar</a>
        </div>

    </form>
    </div>
</x-app-layout>

