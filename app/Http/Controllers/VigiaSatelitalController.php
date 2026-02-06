<?php

namespace App\Http\Controllers;

use App\Models\OrganizationAlertSetting;
use Illuminate\Http\Request;

class VigiaSatelitalController extends Controller
{
    public function index()
    {
        $this->ensurePlanAccess();

        return view('vigia-satelital.index', [
            'section' => 'mapas',
        ]);
    }

    public function mapas()
    {
        $this->ensurePlanAccess();

        $organizationId = auth()->user()->organization_id;
        $farms = collect();
        if ($organizationId) {
            $farms = \App\Models\Farm::where('organization_id', $organizationId)
                ->whereNotNull('polygon_coordinates')
                ->get(['id', 'name', 'polygon_coordinates', 'lat', 'lng']);
        }

        $farmsGeo = $farms->map(function ($farm) {
            return [
                'id' => $farm->id,
                'name' => $farm->name,
                'polygon' => $farm->polygon_coordinates ? json_decode($farm->polygon_coordinates, true) : null,
                'lat' => $farm->lat,
                'lng' => $farm->lng,
            ];
        })->values();

        return view('vigia-satelital.index', [
            'section' => 'mapas',
            'farmsGeo' => $farmsGeo,
        ]);
    }

    public function alertas()
    {
        $this->ensurePlanAccess();

        return view('vigia-satelital.index', [
            'section' => 'alertas',
        ]);
    }

    public function acciones()
    {
        $this->ensurePlanAccess();

        return view('vigia-satelital.index', [
            'section' => 'acciones',
        ]);
    }

    public function configuracion()
    {
        $this->ensurePlanAccess();

        $organization = auth()->user()->organization;
        if (!$organization) {
            return redirect()->route('dashboard')
                ->with('error', 'Necesitas una organización activa para configurar Vigía Satelital.');
        }
        $settings = OrganizationAlertSetting::firstOrCreate(
            ['organization_id' => $organization->id]
        );

        return view('vigia-satelital.config', compact('settings'));
    }

    public function updateConfiguracion(Request $request)
    {
        $this->ensurePlanAccess();

        $organization = auth()->user()->organization;
        if (!$organization) {
            return redirect()->route('dashboard')
                ->with('error', 'Necesitas una organización activa para configurar Vigía Satelital.');
        }
        $settings = OrganizationAlertSetting::firstOrCreate(
            ['organization_id' => $organization->id]
        );

        $data = $request->validate([
            'ndvi_enabled' => ['nullable', 'boolean'],
            'ndmi_enabled' => ['nullable', 'boolean'],
            'nbr_enabled' => ['nullable', 'boolean'],
            'ndvi_drop_threshold' => ['required', 'numeric', 'min:0', 'max:1'],
            'ndmi_drop_threshold' => ['required', 'numeric', 'min:0', 'max:1'],
            'nbr_drop_threshold' => ['required', 'numeric', 'min:0', 'max:1'],
            'cloud_max_percent' => ['required', 'integer', 'min:0', 'max:100'],
            'frequency' => ['required', 'in:weekly'],
        ]);

        $data['ndvi_enabled'] = !empty($data['ndvi_enabled']);
        $data['ndmi_enabled'] = !empty($data['ndmi_enabled']);
        $data['nbr_enabled'] = !empty($data['nbr_enabled']);

        $settings->update($data);

        return redirect()->route('vigia-satelital.config')
            ->with('success', 'Configuración actualizada.');
    }

    private function ensurePlanAccess(): void
    {
        $user = auth()->user();
        if ($user->is_admin) {
            return;
        }

        $planName = $user->organization?->plan?->name;
        $allowed = $planName && in_array(mb_strtolower($planName), ['profesional', 'professional', 'empresa', 'enterprise'], true);

        if (!$allowed) {
            abort(403, 'Vigía Satelital disponible solo en planes Profesional y Empresa.');
        }
    }
}
