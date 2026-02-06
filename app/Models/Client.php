<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'number',
        'name',
        'domicilio',
        'altura',
        'localidad',
        'provincia',
        'pais',
        'status',
        'cuit',
        'email',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($client) {
            if (!$client->number && $client->organization_id) {
                $lastNumber = self::where('organization_id', $client->organization_id)
                    ->orderBy('number', 'desc')
                    ->first()?->number ?? 0;

                $client->number = $lastNumber + 1;
            }
        });
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function farms(): HasMany
    {
        return $this->hasMany(Farm::class);
    }

    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function registrosTecnicos(): HasMany
    {
        return $this->hasMany(RegistroTecnico::class);
    }

    public function clientGroupMembers(): HasMany
    {
        return $this->hasMany(ClientGroupMember::class);
    }
}
