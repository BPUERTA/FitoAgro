<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RegistroTecnico extends Model
{
    use HasFactory;

    public const STATUS_ABIERTO = 'abierto';
    public const STATUS_EN_PROCESO = 'en_proceso';
    public const STATUS_CERRADO = 'cerrado';
    public const STATUS_CANCELADO = 'cancelado';

    public const OBJECTIVE_MALEZA = 'maleza';
    public const OBJECTIVE_PLAGA = 'plaga';
    public const OBJECTIVE_ENFERMEDAD = 'enfermedad';

    protected $fillable = [
        'organization_id',
        'client_id',
        'farm_id',
        'lot_id',
        'code',
        'status',
        'objectives',
        'note',
        'lat',
        'lng',
        'photos',
        'audio_path',
        'created_by',
        'closed_by',
        'canceled_by',
        'canceled_reason',
    ];

    protected $casts = [
        'objectives' => 'array',
        'photos' => 'array',
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registro) {
            if ($registro->code || !$registro->organization_id) {
                return;
            }

            $prefix = 'RT-';

            $last = self::where('organization_id', $registro->organization_id)
                ->where('code', 'like', $prefix . '%')
                ->orderByDesc('code')
                ->lockForUpdate()
                ->first();

            $lastNumber = 0;
            if ($last?->code) {
                $lastNumber = (int) substr($last->code, strlen($prefix));
            }

            $registro->code = $prefix . str_pad((string) ($lastNumber + 1), 8, '0', STR_PAD_LEFT);
        });
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class);
    }

    public function lot(): BelongsTo
    {
        return $this->belongsTo(FarmLot::class);
    }

    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function scopeForOrganization($query, ?int $organizationId)
    {
        if (!$organizationId) {
            return $query;
        }

        return $query->where('organization_id', $organizationId);
    }

    public function isClosedOrCanceled(): bool
    {
        return in_array($this->status, [self::STATUS_CERRADO, self::STATUS_CANCELADO], true);
    }
}
