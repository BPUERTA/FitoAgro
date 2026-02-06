<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'descripcion',
        'marca_comercial',
        'principio_activo',
        'concentracion',
        'formulacion',
        'clase_toxicidad',
        'uso_declarado',
        'um_dosis',
        'um_total',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_product')
            ->withTimestamps();
    }

    public function workOrderProducts()
    {
        return $this->hasMany(WorkOrderProduct::class);
    }
}
