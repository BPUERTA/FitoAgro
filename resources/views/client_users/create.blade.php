@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4"> <!-- gap reducido -->
        <!-- Tarjeta izquierda: Buscador de CUIT y datos del cliente -->
        <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl p-4"> <!-- padding reducido -->
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Buscar Cliente por CUIT</h2>
            <form id="search-cuit-form" class="mb-4">
                <div class="flex gap-2">
                    <input type="text" name="cuit" id="cuit" placeholder="Ingresar CUIT/L" class="w-full rounded border-gray-300 focus:border-green-500 focus:ring-green-500">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Buscar</button>
                </div>
            </form>
            <div id="cliente-info" class="hidden mt-4">
                <div class="mb-2">
                    <span class="font-medium">Nombre completo:</span> <span id="cliente-nombre"></span>
                </div>
                <div class="mb-2">
                    <span class="font-medium">Correo electrónico:</span> <span id="cliente-email"></span>
                </div>
                <div class="mb-2">
                    <span class="font-medium">Usuario generado:</span> <span id="usuario-existe"></span>
                </div>
                <div class="flex gap-2 mt-4">
                    <button id="generar-usuario-btn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded hidden">Generar usuario</button>
                    <button id="actualizar-usuario-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded hidden">Actualizar datos</button>
                </div>
            </div>
        </div>
        <!-- Tarjeta derecha: Formulario o datos del usuario cliente -->
        <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl p-4"> <!-- padding reducido -->
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Datos de Usuario Cliente</h2>
            <div id="usuario-form-container">
                <!-- Aquí se mostrará el formulario de creación o los datos del usuario cliente -->
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('search-cuit-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const cuit = document.getElementById('cuit').value;
    if (!cuit) return;
    document.getElementById('cliente-info').classList.add('hidden');
    document.getElementById('usuario-form-container').innerHTML = '';

    const response = await fetch(`/api/clientes/buscar-por-cuit?cuit=${encodeURIComponent(cuit)}`);
    const data = await response.json();

    if (data.success) {
        // Mostrar todos los datos del cliente en la izquierda
        document.getElementById('cliente-info').innerHTML = `
            <div class='mb-2'><span class='font-medium'>Nombre completo:</span> ${data.cliente.name}</div>
            <div class='mb-2'><span class='font-medium'>CUIT:</span> ${data.cliente.cuit}</div>
            <div class='mb-2'><span class='font-medium'>Correo electrónico:</span> ${data.cliente.email}</div>
            <div class='mb-2'><span class='font-medium'>Domicilio:</span> ${data.cliente.domicilio || ''}</div>
            <div class='mb-2'><span class='font-medium'>Localidad:</span> ${data.cliente.localidad || ''}</div>
            <div class='mb-2'><span class='font-medium'>Provincia:</span> ${data.cliente.provincia || ''}</div>
            <div class='mb-2'><span class='font-medium'>País:</span> ${data.cliente.pais || ''}</div>
            <div class='mb-2'><span class='font-medium'>Estado:</span> ${data.cliente.status || ''}</div>
            <div class='mb-2'><span class='font-medium'>Usuario generado:</span> <span id='usuario-existe'></span></div>
            <div class='flex gap-2 mt-4'>
                <button id='generar-usuario-btn' class='bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded hidden'>Generar usuario</button>
                <button id='actualizar-usuario-btn' class='bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded hidden'>Actualizar datos</button>
            </div>
        `;
        document.getElementById('cliente-info').classList.remove('hidden');
        if (data.usuario) {
            document.getElementById('usuario-existe').textContent = 'Sí';
            document.getElementById('generar-usuario-btn').classList.add('hidden');
            if (data.usuario.email !== data.cliente.email || data.usuario.nombre !== data.cliente.name) {
                document.getElementById('actualizar-usuario-btn').classList.remove('hidden');
            } else {
                document.getElementById('actualizar-usuario-btn').classList.add('hidden');
            }
            // Mostrar datos usuario y cliente a la derecha
            document.getElementById('usuario-form-container').innerHTML = `
                <div class='mb-4'><b>Datos del Cliente</b></div>
                <div class='mb-2'><b>Nombre:</b> ${data.cliente.name}</div>
                <div class='mb-2'><b>CUIT:</b> ${data.cliente.cuit}</div>
                <div class='mb-2'><b>Email:</b> ${data.cliente.email}</div>
                <div class='mb-4'><b>Datos del Usuario Cliente</b></div>
                <form id='crear-usuario-form'>
                    <input type='hidden' name='cuit' value='${data.cliente.cuit}'>
                    <div class='mb-4'>
                        <label class='block text-sm font-medium text-gray-700'>Nombre</label>
                        <input type='text' name='nombre' value='${data.usuario.nombre}' required class='mt-1 block w-full rounded border-gray-300'>
                    </div>
                    <div class='mb-4'>
                        <label class='block text-sm font-medium text-gray-700'>Correo electrónico</label>
                        <input type='email' name='email' value='${data.usuario.email}' required class='mt-1 block w-full rounded border-gray-300'>
                    </div>
                    <div class='mb-4'>
                        <label class='block text-sm font-medium text-gray-700'>Contraseña (dejar vacío para no cambiar)</label>
                        <input type='password' name='password' class='mt-1 block w-full rounded border-gray-300'>
                    </div>
                    <div class='mb-4'>
                        <label class='block text-sm font-medium text-gray-700'>Confirmar Contraseña</label>
                        <input type='password' name='password_confirmation' class='mt-1 block w-full rounded border-gray-300'>
                    </div>
                    <button type='submit' class='bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded'>Actualizar Usuario Cliente</button>
                </form>
            `;
        } else {
            document.getElementById('usuario-existe').textContent = 'No';
            document.getElementById('generar-usuario-btn').classList.remove('hidden');
            document.getElementById('actualizar-usuario-btn').classList.add('hidden');
            // Mostrar formulario de creación a la derecha con datos del cliente
            document.getElementById('usuario-form-container').innerHTML = `
                <div class='mb-4'><b>Datos del Cliente</b></div>
                <div class='mb-2'><b>Nombre:</b> ${data.cliente.name}</div>
                <div class='mb-2'><b>CUIT:</b> ${data.cliente.cuit}</div>
                <div class='mb-2'><b>Email:</b> ${data.cliente.email}</div>
                <div class='mb-4'><b>Crear Usuario Cliente</b></div>
                <form id='crear-usuario-form'>
                    <input type='hidden' name='cuit' value='${data.cliente.cuit}'>
                    <div class='mb-4'>
                        <label class='block text-sm font-medium text-gray-700'>Nombre</label>
                        <input type='text' name='nombre' value='${data.cliente.name}' required class='mt-1 block w-full rounded border-gray-300'>
                    </div>
                    <div class='mb-4'>
                        <label class='block text-sm font-medium text-gray-700'>Correo electrónico</label>
                        <input type='email' name='email' value='${data.cliente.email}' required class='mt-1 block w-full rounded border-gray-300'>
                    </div>
                    <div class='mb-4'>
                        <label class='block text-sm font-medium text-gray-700'>Contraseña</label>
                        <input type='password' name='password' required class='mt-1 block w-full rounded border-gray-300'>
                    </div>
                    <div class='mb-4'>
                        <label class='block text-sm font-medium text-gray-700'>Confirmar Contraseña</label>
                        <input type='password' name='password_confirmation' required class='mt-1 block w-full rounded border-gray-300'>
                    </div>
                    <button type='submit' class='bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded'>Crear Usuario Cliente</button>
                </form>
            `;
        }
    } else {
        document.getElementById('usuario-form-container').innerHTML = `<div class='text-red-600'>No se encontró cliente con ese CUIT.</div>`;
    }
});
// Aquí puedes agregar listeners para los botones de generar/actualizar usuario

// Enviar formulario de creación/actualización de usuario cliente
// Se asume que el formulario se renderiza dinámicamente en #usuario-form-container

document.addEventListener('submit', async function(e) {
    if (e.target && e.target.id === 'crear-usuario-form') {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        // Para actualización, password puede estar vacío
        const payload = {
            cuit: formData.get('cuit'),
            nombre: formData.get('nombre'),
            email: formData.get('email'),
            password: formData.get('password'),
            password_confirmation: formData.get('password_confirmation'),
        };
        const response = await fetch('/api/clientes/usuario-cliente', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload)
        });
        const data = await response.json();
        if (data.success) {
            document.getElementById('usuario-form-container').innerHTML = `
                <div class='text-green-700 mb-2'>${data.message}</div>
                <div class='mb-2'><b>CUIT:</b> ${data.usuario.cuit}</div>
                <div class='mb-2'><b>Nombre:</b> ${data.usuario.nombre}</div>
                <div class='mb-2'><b>Email:</b> ${data.usuario.email}</div>
            `;
        } else {
            document.getElementById('usuario-form-container').innerHTML = `<div class='text-red-600'>${data.message || 'Error al crear/actualizar usuario.'}</div>`;
        }
    }
});
</script>
@endsection
