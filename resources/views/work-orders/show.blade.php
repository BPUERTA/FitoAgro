<x-app-layout>
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Work Order {{ $workOrder->code }}</h1>
                <p class="text-sm text-gray-500">Work order details</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('work-orders.index') }}"
                   class="inline-flex items-center rounded-lg bg-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-400">
                    Back
                </a>

                <a href="{{ route('work-orders.pdf', $workOrder) }}" target="_blank"
                   class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    PDF
                </a>

                <a href="{{ route('work-orders.edit', $workOrder) }}"
                   class="inline-flex items-center rounded-lg bg-purple-600 px-3 py-2 text-sm font-medium text-white hover:bg-purple-700">
                    Edit
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex gap-2">
            <div class="w-[30%] flex-shrink-0">
                <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">General</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Code</label>
                            <p class="mt-1 text-base text-gray-900">{{ $workOrder->code }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Client</label>
                            <p class="mt-1 text-base text-gray-900">
                                <a href="{{ route('clients.show', $workOrder->client) }}" class="text-green-600 hover:text-green-700">
                                    {{ $workOrder->client->name }}
                                </a>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Priority</label>
                            <p class="mt-1 text-base text-gray-900">{{ ucfirst($workOrder->priority) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <p class="mt-1 text-base text-gray-900">{{ ucfirst($workOrder->status) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Scheduled start</label>
                            <p class="mt-1 text-base text-gray-900">{{ $workOrder->scheduled_start_at?->format('Y-m-d H:i') ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Scheduled end</label>
                            <p class="mt-1 text-base text-gray-900">{{ $workOrder->scheduled_end_at?->format('Y-m-d H:i') ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Created by</label>
                            <p class="mt-1 text-base text-gray-900">{{ $workOrder->created_by ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Closed by</label>
                            <p class="mt-1 text-base text-gray-900">{{ $workOrder->closed_by ?? '-' }}</p>
                        </div>
                        @if($workOrder->status === 'cancelado')
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Canceled by</label>
                                <p class="mt-1 text-base text-gray-900">{{ $workOrder->canceled_by ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Canceled reason</label>
                                <p class="mt-1 text-base text-gray-900">{{ $workOrder->canceled_reason ?? '-' }}</p>
                            </div>
                        @endif
                        @if($workOrder->description)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Description</label>
                                <p class="mt-1 text-base text-gray-900">{{ $workOrder->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex-1 flex flex-col gap-6">
                <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Farms</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse($workOrder->farms->groupBy('bloque') as $block => $farms)
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 text-sm font-semibold text-gray-900">
                                    Block {{ $block }}
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-white">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Farm</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ha</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Distance</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Applied</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Line status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white">
                                            @foreach($farms as $farm)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $farm->exploitation_name }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">{{ number_format($farm->has, 2) }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $farm->distancia_poblado ?? '-' }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $farm->fecha_aplicacion?->format('Y-m-d') ?? '-' }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">{{ ucfirst($farm->line_status) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No farms added.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Products</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse($productsByBlock as $block => $products)
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 text-sm font-semibold text-gray-900">
                                    Block {{ $block }}
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-white">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Dose</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Block ha</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Line total</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit price</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Line cost</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Note</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white">
                                            @foreach($products as $product)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $product->product_name }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">
                                                        {{ number_format($product->dosis, 2) }} {{ $product->um_dosis }}
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">{{ number_format($product->total_has_bloque, 2) }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">
                                                        {{ number_format($product->total_linea_producto, 2) }} {{ $product->um_total }}
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $product->precio_unitario ?? '-' }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $product->total_costo_linea ?? '-' }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $product->nota ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No products added.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        @if(auth()->check() && auth()->user()->is_admin)
            <div class="mt-6 border-t border-gray-200 pt-6">
                <form action="{{ route('work-orders.destroy', $workOrder) }}" method="POST"
                      onsubmit="return confirm('Delete this work order?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                        Delete Work Order
                    </button>
                </form>
            </div>
        @endif
    </div>
</x-app-layout>
