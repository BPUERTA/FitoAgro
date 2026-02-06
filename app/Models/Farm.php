<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'client_group_id',
        'organization_id',
        'name',
        'has',
        'distancia_poblado',
        'status',
        'polygon_coordinates',
        'lat',
        'lng',
        'alert_ndvi',
        'alert_ndmi',
        'alert_nbr',
    ];

    protected $casts = [
        'has' => 'decimal:2',
        'distancia_poblado' => 'decimal:2',
        'status' => 'boolean',
        'alert_ndvi' => 'boolean',
        'alert_ndmi' => 'boolean',
        'alert_nbr' => 'boolean',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function clientGroup()
    {
        return $this->belongsTo(ClientGroup::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function workOrderFarms()
    {
        return $this->hasMany(WorkOrderFarm::class, 'exploitation_id');
    }

    public function registrosTecnicos()
    {
        return $this->hasMany(RegistroTecnico::class);
    }

    public function lots()
    {
        return $this->hasMany(FarmLot::class);
    }
}
