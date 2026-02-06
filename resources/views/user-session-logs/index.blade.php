<x-app-layout>
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Historial de ingreso y permanencia</h1>
                <p class="text-sm text-gray-500">Sesiones por usuario (solo superadmin).</p>
            </div>
        </div>

        <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Usuario</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Organización</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Ingreso</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Salida</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Permanencia</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                        @php
                            $end = $log->logout_at ?? now();
                            $duration = $log->login_at ? $log->login_at->diffForHumans($end, [
                                'parts' => 2,
                                'short' => true,
                            ]) : '—';
                        @endphp
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                {{ $log->user?->name ?? '—' }}
                                @if($log->user?->email)
                                    <div class="text-xs text-gray-500">{{ $log->user->email }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $log->user?->organization?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $log->login_at?->format('d/m/Y H:i') ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $log->logout_at?->format('d/m/Y H:i') ?? 'En sesión' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $duration }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $log->ip_address ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">No hay sesiones registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>
</x-app-layout>
