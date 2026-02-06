<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use App\Models\RegistroTecnico;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'client_id',
        'registro_tecnico_id',
        'description',
        'code',
        'priority',
        'scheduled_start_at',
        'scheduled_end_at',
        'status',
        'created_by',
        'closed_by',
        'canceled_by',
        'canceled_reason',
    ];

    protected $casts = [
        'scheduled_start_at' => 'datetime',
        'scheduled_end_at' => 'datetime',
    ];

    public const PRIORITY_LOW = 'low';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_HIGH = 'high';
    public const PRIORITY_URGENT = 'urgent';

    public const STATUS_PENDIENTE = 'pendiente';
    public const STATUS_ABIERTO = 'abierto';
    public const STATUS_CERRADO = 'cerrado';
    public const STATUS_CANCELADO = 'cancelado';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($workOrder) {
            if ($workOrder->code || !$workOrder->organization_id) {
                return;
            }

            $year = Carbon::now()->format('Y');
            $prefix = "OT-{$year}-";

            $lastOrder = self::where('organization_id', $workOrder->organization_id)
                ->where('code', 'like', $prefix . '%')
                ->orderByDesc('code')
                ->lockForUpdate()
                ->first();

            $lastNumber = 0;
            if ($lastOrder?->code) {
                $lastNumber = (int) substr($lastOrder->code, strlen($prefix));
            }

            $workOrder->code = $prefix . str_pad((string) ($lastNumber + 1), 6, '0', STR_PAD_LEFT);
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

    public function registroTecnico(): BelongsTo
    {
        return $this->belongsTo(RegistroTecnico::class);
    }

    public function farms(): HasMany
    {
        return $this->hasMany(WorkOrderFarm::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(WorkOrderProduct::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(WorkOrderStatusLog::class);
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
