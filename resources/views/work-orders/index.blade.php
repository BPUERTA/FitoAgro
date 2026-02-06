<x-app-layout>
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Work Orders</h1>
                    <p class="text-sm text-gray-500">Manage work orders.</p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('work-orders.create') }}"
                       class="inline-flex items-center justify-center rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 whitespace-nowrap">
                        New Work Order
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl overflow-hidden mb-6 p-4">
            <form method="GET" action="{{ route('work-orders.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                        <option value="">All</option>
                        <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pending</option>
                        <option value="abierto" {{ request('status') == 'abierto' ? 'selected' : '' }}>Open</option>
                        <option value="cerrado" {{ request('status') == 'cerrado' ? 'selected' : '' }}>Closed</option>
                        <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Canceled</option>
                    </select>
                </div>

                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                    <select name="priority" id="priority" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                        <option value="">All</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>

                <div class="md:col-span-1 flex items-end gap-2">
                    <button type="submit" class="inline-flex items-center rounded-lg bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                        Filter
                    </button>
                    <a href="{{ route('work-orders.index') }}" class="inline-flex items-center rounded-lg bg-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-400">
                        Clear
                    </a>
                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
                <span class="text-sm font-medium text-gray-700">List</span>
                <span class="text-xs text-gray-500">Total: {{ $workOrders->total() }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Code</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Priority</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Start</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Farms</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($workOrders as $workOrder)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $workOrder->code }}</td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $workOrder->client->name }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ ucfirst($workOrder->priority) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $workOrder->scheduled_start_at?->format('Y-m-d H:i') ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ ucfirst($workOrder->status) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $workOrder->farms->count() }}
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <a href="{{ route('work-orders.show', $workOrder) }}"
                                       class="inline-flex items-center rounded-lg bg-purple-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-purple-700">
                                        View
                                    </a>
                                    <a href="{{ route('work-orders.edit', $workOrder) }}"
                                       class="ml-2 inline-flex items-center rounded-lg bg-gray-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-gray-700">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">
                                    No work orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($workOrders->hasPages())
                <div class="border-t border-gray-200 px-4 py-3">
                    {{ $workOrders->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
