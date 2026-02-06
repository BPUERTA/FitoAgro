<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use App\Models\FarmLot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FarmLotController extends Controller
{
    public function store(Request $request, Farm $farm): RedirectResponse
    {
        $this->authorizeFarmAccess($farm);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'has' => ['required', 'numeric', 'min:0.01'],
            'is_agricola' => ['nullable', 'boolean'],
            'is_ganadera' => ['nullable', 'boolean'],
            'is_otro' => ['nullable', 'boolean'],
            'vigia_alerts_enabled' => ['nullable', 'boolean'],
            'polygon_coordinates' => ['nullable', 'string'],
        ]);

        $farmHas = (float) $farm->has;
        if ($farmHas <= 0) {
            return back()->withErrors([
                'has' => 'La explotación debe tener hectáreas totales mayores a 0 para crear lotes.',
            ])->withInput();
        }

        $currentSum = (float) $farm->lots()->sum('has');
        $newHas = (float) $validated['has'];
        if ($currentSum + $newHas > $farmHas) {
            return back()->withErrors([
                'has' => 'La suma de hectáreas de los lotes no puede superar el total de la explotación.',
            ])->withInput();
        }

        $farm->lots()->create([
            'name' => $validated['name'],
            'has' => $newHas,
            'is_agricola' => !empty($validated['is_agricola']),
            'is_ganadera' => !empty($validated['is_ganadera']),
            'is_otro' => !empty($validated['is_otro']),
            'vigia_alerts_enabled' => !empty($validated['vigia_alerts_enabled']),
            'polygon_coordinates' => $validated['polygon_coordinates'] ?? null,
        ]);

        return back()->with('success', 'Lote creado exitosamente.');
    }

    public function update(Request $request, Farm $farm, FarmLot $lot): RedirectResponse
    {
        $this->authorizeFarmAccess($farm);

        if ($lot->farm_id !== $farm->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'has' => ['required', 'numeric', 'min:0.01'],
            'is_agricola' => ['nullable', 'boolean'],
            'is_ganadera' => ['nullable', 'boolean'],
            'is_otro' => ['nullable', 'boolean'],
            'vigia_alerts_enabled' => ['nullable', 'boolean'],
            'polygon_coordinates' => ['nullable', 'string'],
        ]);

        $farmHas = (float) $farm->has;
        if ($farmHas <= 0) {
            return back()->withErrors([
                'has' => 'La explotación debe tener hectáreas totales mayores a 0 para actualizar lotes.',
            ])->withInput();
        }

        $currentSum = (float) $farm->lots()
            ->where('id', '!=', $lot->id)
            ->sum('has');
        $newHas = (float) $validated['has'];
        if ($currentSum + $newHas > $farmHas) {
            return back()->withErrors([
                'has' => 'La suma de hectáreas de los lotes no puede superar el total de la explotación.',
            ])->withInput();
        }

        $lot->update([
            'name' => $validated['name'],
            'has' => $newHas,
            'is_agricola' => !empty($validated['is_agricola']),
            'is_ganadera' => !empty($validated['is_ganadera']),
            'is_otro' => !empty($validated['is_otro']),
            'vigia_alerts_enabled' => !empty($validated['vigia_alerts_enabled']),
            'polygon_coordinates' => $validated['polygon_coordinates'] ?? null,
        ]);

        return back()->with('success', 'Lote actualizado exitosamente.');
    }

    public function destroy(Farm $farm, FarmLot $lot): RedirectResponse
    {
        $this->authorizeFarmAccess($farm);

        if ($lot->farm_id !== $farm->id) {
            abort(404);
        }

        $lot->delete();

        return back()->with('success', 'Lote eliminado.');
    }

    private function authorizeFarmAccess(Farm $farm): void
    {
        $user = auth()->user();
        if (!$user) {
            abort(403);
        }

        if (!$user->is_admin && $farm->organization_id != $user->organization_id) {
            abort(403);
        }
    }
}
