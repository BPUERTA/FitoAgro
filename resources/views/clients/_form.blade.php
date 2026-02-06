<div>
    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
        Nombre / Razón Social
    </label>
    <input 
        type="text" 
        id="name" 
        name="name" 
        value="{{ old('name', isset($client) ? $client->name : '') }}"
        required
        placeholder="Ej: Juan García"
        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
    />
    @error('name')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
<div>
    <label for="domicilio" class="block text-sm font-medium text-gray-700 mb-1">
        Domicilio
    </label>
    <input 
        type="text" 
        id="domicilio" 
        name="domicilio" 
        value="{{ old('domicilio', isset($client) ? $client->domicilio : '') }}"
        placeholder="Ej: Calle Principal"
        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
    />
    @error('domicilio')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
<div>
    <label for="altura" class="block text-sm font-medium text-gray-700 mb-1">
        Altura
    </label>
    <input 
        type="text" 
        id="altura" 
        name="altura" 
        value="{{ old('altura', isset($client) ? $client->altura : '') }}"
        placeholder="Ej: 123"
        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
    />
    @error('altura')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
<div>
    <label for="localidad" class="block text-sm font-medium text-gray-700 mb-1">
        Localidad
    </label>
    <input 
        type="text" 
        id="localidad" 
        name="localidad" 
        value="{{ old('localidad', isset($client) ? $client->localidad : '') }}"
        placeholder="Ej: Buenos Aires"
        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
    />
    @error('localidad')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
<div>
    <label for="provincia" class="block text-sm font-medium text-gray-700 mb-1">
        Provincia
    </label>
    <input 
        type="text" 
        id="provincia" 
        name="provincia" 
        value="{{ old('provincia', isset($client) ? $client->provincia : '') }}"
        placeholder="Ej: Buenos Aires"
        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
    />
    @error('provincia')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
<div>
    <label for="pais" class="block text-sm font-medium text-gray-700 mb-1">
        País
    </label>
    <input 
        type="text" 
        id="pais" 
        name="pais" 
        value="{{ old('pais', isset($client) ? $client->pais : '') }}"
        placeholder="Ej: Argentina"
        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
    />
    @error('pais')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
<div>
    <label for="cuit" class="block text-sm font-medium text-gray-700 mb-1">
        CUIT/L
    </label>
    <input 
        type="text" 
        id="cuit" 
        name="cuit" 
        value="{{ old('cuit', isset($client) ? $client->cuit : '') }}"
        placeholder="Ej: 20-12345678-9"
        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
    />
    @error('cuit')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
<div>
    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
        Correo electrónico
    </label>
    <input 
        type="email" 
        id="email" 
        name="email" 
        value="{{ old('email', isset($client) ? $client->email : '') }}"
        placeholder="Ej: cliente@email.com"
        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
    />
    @error('email')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
<div>
    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
        Estado
    </label>
    <select
        id="status"
        name="status"
        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
        required
    >
        <option value="active" {{ old('status', isset($client) ? $client->status : 'active') === 'active' ? 'selected' : '' }}>Activo</option>
        <option value="inactive" {{ old('status', isset($client) ? $client->status : 'active') === 'inactive' ? 'selected' : '' }}>Inactivo</option>
    </select>
    @error('status')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
