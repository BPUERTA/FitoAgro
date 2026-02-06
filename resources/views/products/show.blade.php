<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalles del Producto') }}
            </h2>
            <div class="flex gap-2">
                @if(auth()->user()->is_admin)
                    <a href="{{ route('products.edit', $product) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Editar
                    </a>
                @endif
                <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full divide-y divide-gray-200">
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">
                                    ID
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $product->id }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">
                                    Descripción
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $product->descripcion ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">
                                    Marca Comercial
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $product->marca_comercial ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">
                                    Principio Activo
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $product->principio_activo ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">
                                    Concentración
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $product->concentracion ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">
                                    Formulación
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $product->formulacion ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">
                                    Clase Toxicidad
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $product->clase_toxicidad ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">
                                    Uso Declarado
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $product->uso_declarado ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">
                                    UM Dosis
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $product->um_dosis ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">
                                    UM Total
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $product->um_total ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">
                                    Estado
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($product->status)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Activo
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inactivo
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">
                                    Fecha de Creación
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $product->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">
                                    Última Actualización
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $product->updated_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    @if(auth()->user()->is_admin)
                        <div class="mt-6">
                            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Eliminar Producto
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
