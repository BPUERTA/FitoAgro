<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkOrderFarm extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'work_order_id',
        'bloque',
        'exploitation_id',
        'exploitation_name',
        'has',
        'distancia_poblado',
        'line_status',
        'fecha_aplicacion',
    ];

    protected $casts = [
        'has' => 'decimal:2',
        'distancia_poblado' => 'decimal:2',
        'fecha_aplicacion' => 'date',
    ];

    public const STATUS_PENDIENTE = 'pendiente';
    public const STATUS_CERRADO = 'cerrado';

    protected static function boot()
    {
        parent::boot();

        static::saving(function (self $farm) {
            $farm->line_status = $farm->fecha_aplicacion ? self::STATUS_CERRADO : self::STATUS_PENDIENTE;
        });
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function exploitation(): BelongsTo
    {
        return $this->belongsTo(Farm::class, 'exploitation_id');
    }
}
