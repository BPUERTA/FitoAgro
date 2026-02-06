<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'organization_id',
        'contractor_id',
        'name',
        'servicio',
        'tipo_servicio',
        'dominio',
        'matricula',
        'tipo_equipo',
        'tipo_propiedad',
        'contratistas',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'servicio' => 'array',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function contractor()
    {
        return $this->belongsTo(Contractor::class);
    }
    
    public function getTipoEquipoLabelAttribute()
    {
        $labels = [
            'fumigacion' => 'Fumigación',
            'fertilizacion_liquida' => 'Fertilización Líquida',
            'fertilizacion_solida' => 'Fertilización Sólida',
            'siembra' => 'Siembra',
            'cosecha' => 'Cosecha',
            'laboreo' => 'Laboreo',
        ];
        return $labels[$this->tipo_equipo] ?? $this->tipo_equipo;
    }
}
