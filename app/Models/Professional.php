<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professional extends Model
{
    protected $fillable = [
        'organization_id',
        'nombre_completo',
        'matricula',
        'numero_registro',
        'localidad',
        'provincia',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
