<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Producto') }}
            </h2>
            <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('products.update', $product) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Descripción -->
                            <div class="md:col-span-2">
                                <label for="descripcion" class="block text-gray-700 text-sm font-bold mb-2">
                                    Descripción <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="descripcion" id="descripcion" value="{{ old('descripcion', $product->descripcion) }}" 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('descripcion') border-red-500 @enderror" 
                                       required>
                                @error('descripcion')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Marca Comercial -->
                            <div>
                                <label for="marca_comercial" class="block text-gray-700 text-sm font-bold mb-2">
                                    Marca Comercial
                                </label>
                                <input type="text" name="marca_comercial" id="marca_comercial" value="{{ old('marca_comercial', $product->marca_comercial) }}" 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('marca_comercial') border-red-500 @enderror">
                                @error('marca_comercial')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Principio Activo -->
                            <div>
                                <label for="principio_activo" class="block text-gray-700 text-sm font-bold mb-2">
                                    Principio Activo
                                </label>
                                <input type="text" name="principio_activo" id="principio_activo" value="{{ old('principio_activo', $product->principio_activo) }}" 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('principio_activo') border-red-500 @enderror">
                                @error('principio_activo')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Concentración -->
                            <div>
                                <label for="concentracion" class="block text-gray-700 text-sm font-bold mb-2">
                                    Concentración
                                </label>
                                <input type="text" name="concentracion" id="concentracion" value="{{ old('concentracion', $product->concentracion) }}" 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('concentracion') border-red-500 @enderror">
                                @error('concentracion')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Formulación -->
                            <div>
                                <label for="formulacion" class="block text-gray-700 text-sm font-bold mb-2">
                                    Formulación
                                </label>
                                <select name="formulacion" id="formulacion" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('formulacion') border-red-500 @enderror"
                                        onchange="updateFormulacionDesc(this.value)">
                                    <option value="">Seleccionar...</option>
                                    <optgroup label="Formulaciones Líquidas">
                                        <option value="SL (Concentrado Soluble)" {{ old('formulacion', $product->formulacion) == 'SL (Concentrado Soluble)' ? 'selected' : '' }}>SL (Concentrado Soluble)</option>
                                        <option value="EC (Concentrado Emulsionable)" {{ old('formulacion', $product->formulacion) == 'EC (Concentrado Emulsionable)' ? 'selected' : '' }}>EC (Concentrado Emulsionable)</option>
                                        <option value="SC (Suspensión Concentrada)" {{ old('formulacion', $product->formulacion) == 'SC (Suspensión Concentrada)' ? 'selected' : '' }}>SC (Suspensión Concentrada)</option>
                                        <option value="OD (Dispersión en Aceite)" {{ old('formulacion', $product->formulacion) == 'OD (Dispersión en Aceite)' ? 'selected' : '' }}>OD (Dispersión en Aceite)</option>
                                        <option value="SE (Suspo-emulsión)" {{ old('formulacion', $product->formulacion) == 'SE (Suspo-emulsión)' ? 'selected' : '' }}>SE (Suspo-emulsión)</option>
                                        <option value="ME (Micro-emulsión)" {{ old('formulacion', $product->formulacion) == 'ME (Micro-emulsión)' ? 'selected' : '' }}>ME (Micro-emulsión)</option>
                                        <option value="CS (Suspensión de Encapsulados)" {{ old('formulacion', $product->formulacion) == 'CS (Suspensión de Encapsulados)' ? 'selected' : '' }}>CS (Suspensión de Encapsulados)</option>
                                    </optgroup>
                                    <optgroup label="Formulaciones Sólidas">
                                        <option value="WG / WDG (Gránulos Dispersables en Agua)" {{ old('formulacion', $product->formulacion) == 'WG / WDG (Gránulos Dispersables en Agua)' ? 'selected' : '' }}>WG / WDG (Gránulos Dispersables en Agua)</option>
                                        <option value="SG (Gránulos Solubles)" {{ old('formulacion', $product->formulacion) == 'SG (Gránulos Solubles)' ? 'selected' : '' }}>SG (Gránulos Solubles)</option>
                                        <option value="WP (Polvo Mojable)" {{ old('formulacion', $product->formulacion) == 'WP (Polvo Mojable)' ? 'selected' : '' }}>WP (Polvo Mojable)</option>
                                        <option value="SP (Polvo Soluble)" {{ old('formulacion', $product->formulacion) == 'SP (Polvo Soluble)' ? 'selected' : '' }}>SP (Polvo Soluble)</option>
                                    </optgroup>
                                    <optgroup label="Fertilizantes">
                                        <option value="Fertilizante Líquido" {{ old('formulacion', $product->formulacion) == 'Fertilizante Líquido' ? 'selected' : '' }}>Fertilizante Líquido</option>
                                        <option value="Fertilizante Sólido" {{ old('formulacion', $product->formulacion) == 'Fertilizante Sólido' ? 'selected' : '' }}>Fertilizante Sólido</option>
                                        <option value="Fertilizante Foliar" {{ old('formulacion', $product->formulacion) == 'Fertilizante Foliar' ? 'selected' : '' }}>Fertilizante Foliar</option>
                                        <option value="Fertilizante Biológico" {{ old('formulacion', $product->formulacion) == 'Fertilizante Biológico' ? 'selected' : '' }}>Fertilizante Biológico</option>
                                    </optgroup>
                                </select>
                                @error('formulacion')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                                <div id="formulacion_desc" class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded text-sm text-gray-700" style="display: none;"></div>
                            </div>

                            <!-- Clase Toxicidad -->
                            <div>
                                <label for="clase_toxicidad" class="block text-gray-700 text-sm font-bold mb-2">
                                    Clase Toxicidad
                                </label>
                                <select name="clase_toxicidad" id="clase_toxicidad" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('clase_toxicidad') border-red-500 @enderror">
                                    <option value="">Seleccionar...</option>
                                    <option value="I" {{ old('clase_toxicidad', $product->clase_toxicidad) == 'I' ? 'selected' : '' }}>I</option>
                                    <option value="II" {{ old('clase_toxicidad', $product->clase_toxicidad) == 'II' ? 'selected' : '' }}>II</option>
                                    <option value="III" {{ old('clase_toxicidad', $product->clase_toxicidad) == 'III' ? 'selected' : '' }}>III</option>
                                    <option value="IV" {{ old('clase_toxicidad', $product->clase_toxicidad) == 'IV' ? 'selected' : '' }}>IV</option>
                                    <option value="V" {{ old('clase_toxicidad', $product->clase_toxicidad) == 'V' ? 'selected' : '' }}>V</option>
                                </select>
                                @error('clase_toxicidad')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Uso Declarado -->
                            <div class="md:col-span-2">
                                <label for="uso_declarado" class="block text-gray-700 text-sm font-bold mb-2">
                                    Uso Declarado
                                </label>
                                <input type="text" name="uso_declarado" id="uso_declarado" value="{{ old('uso_declarado', $product->uso_declarado) }}" 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('uso_declarado') border-red-500 @enderror">
                                @error('uso_declarado')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- UM Dosis -->
                            <div>
                                <label for="um_dosis" class="block text-gray-700 text-sm font-bold mb-2">
                                    UM Dosis
                                </label>
                                <select name="um_dosis" id="um_dosis" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('um_dosis') border-red-500 @enderror">
                                    <option value="">Seleccionar...</option>
                                    <option value="Cc" {{ old('um_dosis', $product->um_dosis) == 'Cc' ? 'selected' : '' }}>Cc</option>
                                    <option value="Gr" {{ old('um_dosis', $product->um_dosis) == 'Gr' ? 'selected' : '' }}>Gr</option>
                                </select>
                                @error('um_dosis')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- UM Total -->
                            <div>
                                <label for="um_total" class="block text-gray-700 text-sm font-bold mb-2">
                                    UM Total
                                </label>
                                <select name="um_total" id="um_total" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('um_total') border-red-500 @enderror">
                                    <option value="">Seleccionar...</option>
                                    <option value="Lt" {{ old('um_total', $product->um_total) == 'Lt' ? 'selected' : '' }}>Lt</option>
                                    <option value="Kg" {{ old('um_total', $product->um_total) == 'Kg' ? 'selected' : '' }}>Kg</option>
                                </select>
                                @error('um_total')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="mt-4">
                            <label for="status" class="flex items-center">
                                <input type="checkbox" name="status" id="status" value="1" 
                                       class="rounded border-gray-300 text-green-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                       {{ old('status', $product->status) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">Producto activo</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Actualizar Producto
                            </button>
                            <a href="{{ route('products.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const formulacionDescriptions = {
            'SL (Concentrado Soluble)': 'El ingrediente activo se disuelve completamente en agua. Se forma una solución transparente. Son fáciles de usar y requieren poca agitación una vez mezclados. Ejemplo: Glifosato clásico.',
            'EC (Concentrado Emulsionable)': 'El ingrediente activo está disuelto en un solvente derivado del petróleo. Al contacto con el agua forman una emulsión lechosa (blanca). Nota: Suelen tener olor fuerte a solvente y requieren buena agitación inicial.',
            'SC (Suspensión Concentrada)': 'Partículas sólidas muy finas dispersas en agua. Es un líquido espeso (parecido a una pintura o yogurt líquido). Clave: Si se dejan reposar mucho tiempo, el sólido decanta al fondo; requieren agitación constante.',
            'OD (Dispersión en Aceite)': 'Similar al SC, pero el sólido está suspendido en aceite. Ventaja: Mejor adherencia a la hoja y menor evaporación.',
            'SE (Suspo-emulsión)': 'Una mezcla híbrida que contiene una fase sólida (SC) y una fase líquida aceitosa (EC). Permite combinar varios principios activos incompatibles.',
            'ME (Micro-emulsión)': 'Líquidos claros que forman emulsiones termodinámicamente estables con gotas muy pequeñas.',
            'CS (Suspensión de Encapsulados)': 'El principio activo está dentro de microcápsulas poliméricas suspendidas en agua. Liberan el producto lentamente (efecto residual).',
            'WG / WDG (Gránulos Dispersables en Agua)': 'Son pequeños "granos" que, al contacto con el agua, se desintegran y comportan como un polvo. Ventaja: No levantan polvo al cargarlos (más seguros para el operario) y se miden por peso.',
            'SG (Gránulos Solubles)': 'Similar al anterior, pero se disuelven completamente formando una solución verdadera (como el azúcar en té).',
            'WP (Polvo Mojable)': 'Polvo fino que se dispersa en agua. Desventaja: Es tecnología vieja, levanta mucho polvo (peligroso al respirar) y es abrasivo para las pastillas de la pulverizadora.',
            'SP (Polvo Soluble)': 'Polvo que se disuelve totalmente en agua.'
        };

        function updateFormulacionDesc(value) {
            const descDiv = document.getElementById('formulacion_desc');
            if (formulacionDescriptions[value]) {
                descDiv.textContent = formulacionDescriptions[value];
                descDiv.style.display = 'block';
            } else {
                descDiv.style.display = 'none';
            }
        }

        // Mostrar descripción si ya hay una formulación seleccionada al cargar
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('formulacion');
            if (select.value) {
                updateFormulacionDesc(select.value);
            }
        });
    </script>
</x-app-layout>
