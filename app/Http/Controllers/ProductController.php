<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('id', 'desc')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }
        return view('products.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $validated = $request->validate([
            'descripcion' => 'required|string|max:255',
            'marca_comercial' => 'nullable|string|max:255',
            'principio_activo' => 'nullable|string|max:255',
            'concentracion' => 'nullable|string|max:255',
            'formulacion' => 'nullable|string|max:255',
            'clase_toxicidad' => 'nullable|string|max:255',
            'uso_declarado' => 'nullable|string|max:255',
            'um_dosis' => 'nullable|string|max:50',
            'um_total' => 'nullable|string|max:50',
            'status' => 'nullable|boolean',
        ]);

        $validated['status'] = $request->has('status');

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Producto creado exitosamente.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $validated = $request->validate([
            'descripcion' => 'required|string|max:255',
            'marca_comercial' => 'nullable|string|max:255',
            'principio_activo' => 'nullable|string|max:255',
            'concentracion' => 'nullable|string|max:255',
            'formulacion' => 'nullable|string|max:255',
            'clase_toxicidad' => 'nullable|string|max:255',
            'uso_declarado' => 'nullable|string|max:255',
            'um_dosis' => 'nullable|string|max:50',
            'um_total' => 'nullable|string|max:50',
            'status' => 'nullable|boolean',
        ]);

        $validated['status'] = $request->has('status');

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Product $product)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Producto eliminado exitosamente.');
    }

    public function search(Request $request)
    {
        $search = $request->input('q', '');
        $query = Product::where('status', true);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('descripcion', 'like', "%{$search}%")
                  ->orWhere('marca_comercial', 'like', "%{$search}%")
                  ->orWhere('principio_activo', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('descripcion')->limit(50)->get();

        return response()->json([
            'success' => true,
            'products' => $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'descripcion' => $product->descripcion,
                    'marca_comercial' => $product->marca_comercial,
                    'principio_activo' => $product->principio_activo,
                    'um_dosis' => $product->um_dosis,
                    'um_total' => $product->um_total,
                ];
            })
        ]);
    }
}
