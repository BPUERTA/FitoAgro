<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Panel izquierdo: Información de la organización -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-900">{{ $organization->name }}</h1>
                            <p class="text-sm text-gray-500">Detalle de organización</p>
                        </div>

                        <a href="{{ route('organizations.index') }}"
                           class="rounded-lg border border-gray-300 px-3 py-2 text-sm hover:bg-gray-50">
                            Volver
                        </a>
                    </div>

                    <div class="mt-6 grid gap-4">
                        <div class="text-sm">
                            <span class="text-gray-500">ID:</span>
                            <span class="font-medium text-gray-900">{{ $organization->id }}</span>
                        </div>

                        <div class="text-sm">
                            <span class="text-gray-500">Estado:</span>
                            @if($organization->status === 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Activo
                                </span>
                            @elseif($organization->status === 'inactive')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Inactivo
                                </span>
                            @else
                                <span class="font-medium text-gray-900">{{ $organization->status }}</span>
                            @endif
                        </div>

                        <!-- Fechas de prueba -->
                        <div class="border-t pt-4 mt-2">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Período de Prueba</h4>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="text-sm">
                                    <span class="text-gray-500">Inicio:</span>
                                    <span class="font-medium text-gray-900">
                                        {{ $organization->trial_start_date ? $organization->trial_start_date->format('d/m/Y') : 'No definido' }}
                                    </span>
                                </div>
                                <div class="text-sm">
                                    <span class="text-gray-500">Finalización:</span>
                                    <span class="font-medium text-gray-900">
                                        {{ $organization->trial_end_date ? $organization->trial_end_date->format('d/m/Y') : 'No definido' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Fechas de plan pago -->
                        <div class="border-t pt-4 mt-2">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Plan Pago</h4>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="text-sm">
                                    <span class="text-gray-500">Inicio:</span>
                                    <span class="font-medium text-gray-900">
                                        {{ $organization->paid_plan_start_date ? $organization->paid_plan_start_date->format('d/m/Y') : 'No definido' }}
                                    </span>
                                </div>
                                <div class="text-sm">
                                    <span class="text-gray-500">Finalización:</span>
                                    <span class="font-medium text-gray-900">
                                        {{ $organization->paid_plan_end_date ? $organization->paid_plan_end_date->format('d/m/Y') : 'No definido' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Historial de pagos -->
                        <div class="border-t pt-4 mt-2">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Historial de Pagos</h4>
                            @if($organization->payments->count() > 0)
                                <div class="space-y-2">
                                    @foreach($organization->payments as $payment)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ number_format($payment->amount, 2) }} {{ $payment->currency }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $payment->payment_date->format('d/m/Y') }}
                                                    @if($payment->payment_method)
                                                        • {{ $payment->payment_method }}
                                                    @endif
                                                </p>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                @if($payment->status === 'completed') bg-green-100 text-green-800
                                                @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($payment->status === 'failed') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500">No hay pagos registrados</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel derecho: Usuarios de la organización -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl overflow-hidden">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Usuarios</h3>
                        <p class="text-xs text-gray-500 mt-1">Total: {{ $organization->users->count() }}</p>
                    </div>

                    <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                        @forelse($organization->users as $user)
                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $user->nombre }} {{ $user->apellido }}
                                        </p>
                                        <p class="text-xs text-gray-500 truncate mt-0.5">
                                            {{ $user->email }}
                                        </p>
                                        @if($user->nickname)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                                {{ $user->nickname }}
                                            </span>
                                        @endif
                                        @if($user->is_org_admin)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 mt-1 ml-1">
                                                Admin
                                            </span>
                                        @endif
                                    </div>
                                    <button onclick="showUserModal({{ $user->id }})" 
                                       class="ml-2 text-green-600 hover:text-green-700 text-xs font-medium whitespace-nowrap">
                                        Ver
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No hay usuarios asignados a esta organización</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>


                

                <!-- Tarjeta 3: Usuarios Clientes -->
                <div>
                    <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl overflow-hidden">
                        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Usuarios Clientes</h3>
                                <input type="text" id="searchClientUser" placeholder="Buscar usuario..." class="w-full mt-2 mb-2 rounded border-gray-300 focus:border-green-500 focus:ring-green-500">
                                <p class="text-xs text-gray-500 mt-1">Total: {{ $organization->users->where('user_type', 'client')->count() }}</p>
                            </div>
                            <a href="{{ route('client-users.create', $organization) }}" class="bg-green-500 hover:bg-green-700 text-white px-3 py-2 rounded text-sm font-medium">Crear Usuario Cliente</a>
                        </div>
                        <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto" id="clientUserList">
                            @forelse($organization->users->where('user_type', 'client') as $user)
                                <div class="p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $user->nombre }} {{ $user->apellido }}
                                            </p>
                                            <p class="text-xs text-gray-500 truncate mt-0.5">
                                                {{ $user->email }}
                                            </p>
                                            @if($user->nickname)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                                    {{ $user->nickname }}
                                                </span>
                                            @endif
                                        </div>
                                        <button onclick="showUserModal({{ $user->id }})" 
                                           class="ml-2 text-green-600 hover:text-green-700 text-xs font-medium whitespace-nowrap">
                                            Ver
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">No hay usuarios de clientes</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
        </div>
    </div>

    <!-- Modal de Usuario -->
    <div id="userModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
            <!-- Header del Modal -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-900">Detalle del Usuario</h3>
                <button onclick="closeUserModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Contenido del Modal -->
            <div id="userModalContent" class="px-6 py-4 overflow-y-auto max-h-[calc(90vh-8rem)]">
                <div class="flex items-center justify-center py-12">
                    <svg class="animate-spin h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>

            <!-- Footer del Modal -->
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                <button onclick="closeUserModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <script>
        function showUserModal(userId) {
            const modal = document.getElementById('userModal');
            const content = document.getElementById('userModalContent');
            
            // Mostrar modal
            modal.classList.remove('hidden');
            
            // Cargar datos del usuario
            fetch(`/users/${userId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(user => {
                content.innerHTML = `
                    <div class="space-y-6">
                        <!-- Información Principal -->
                        <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg p-4">
                            <div class="flex items-center gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-16 h-16 min-w-[4rem] aspect-square bg-green-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                                        ${user.nombre.charAt(0)}${user.apellido.charAt(0)}
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-gray-900">${user.nombre} ${user.apellido}</h4>
                                    ${user.nickname ? `<p class="text-sm text-gray-600">@${user.nickname}</p>` : ''}
                                    <div class="flex gap-2 mt-2">
                                        ${user.is_admin ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">Superadmin</span>' : ''}
                                        ${user.is_org_admin && !user.is_admin ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Admin de Organización</span>' : ''}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información de Contacto -->
                        <div>
                            <h5 class="text-sm font-semibold text-gray-900 mb-3">Información de Contacto</h5>
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500">Email</p>
                                        <p class="text-sm text-gray-900">${user.email}</p>
                                    </div>
                                </div>
                                ${user.telefono ? `
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500">Teléfono</p>
                                        <p class="text-sm text-gray-900">${user.telefono}</p>
                                    </div>
                                </div>
                                ` : ''}
                                ${user.direccion ? `
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500">Dirección</p>
                                        <p class="text-sm text-gray-900">${user.direccion}</p>
                                    </div>
                                </div>
                                ` : ''}
                            </div>
                        </div>

                        <!-- Información de Organización -->
                        ${user.organization ? `
                        <div>
                            <h5 class="text-sm font-semibold text-gray-900 mb-3">Organización</h5>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-sm font-medium text-gray-900">${user.organization.name}</p>
                                <p class="text-xs text-gray-500 mt-1">ID: ${user.organization.id}</p>
                            </div>
                        </div>
                        ` : ''}

                        <!-- Información del Sistema -->
                        <div>
                            <h5 class="text-sm font-semibold text-gray-900 mb-3">Información del Sistema</h5>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-500">ID de Usuario</p>
                                    <p class="text-sm font-medium text-gray-900">${user.id}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-500">Fecha de Registro</p>
                                    <p class="text-sm font-medium text-gray-900">${user.created_at}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            })
            .catch(error => {
                content.innerHTML = `
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Error al cargar la información del usuario</p>
                    </div>
                `;
            });
        }

        function closeUserModal() {
            document.getElementById('userModal').classList.add('hidden');
        }

        // Cerrar modal al hacer clic fuera de él
        document.getElementById('userModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeUserModal();
            }
        });

        // Cerrar modal con tecla ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeUserModal();
            }
        });
    </script>
</x-app-layout>

