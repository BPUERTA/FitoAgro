<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contractor extends Model
{
    protected $fillable = [
        'organization_id',
        'numero',
        'nombre',
        'cuit',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}
