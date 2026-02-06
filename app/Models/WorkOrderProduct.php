<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkOrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'work_order_id',
        'bloque',
        'total_has_bloque',
        'product_id',
        'product_name',
        'dosis',
        'um_dosis',
        'total_linea_producto',
        'um_total',
        'nota',
        'precio_unitario',
        'total_costo_linea',
    ];

    protected $casts = [
        'total_has_bloque' => 'decimal:2',
        'dosis' => 'decimal:2',
        'total_linea_producto' => 'decimal:2',
        'precio_unitario' => 'decimal:2',
        'total_costo_linea' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (self $product) {
            if ($product->total_has_bloque > 0) {
                if ($product->dosis === null && $product->total_linea_producto !== null) {
                    $product->dosis = $product->total_linea_producto / $product->total_has_bloque;
                }

                if ($product->dosis !== null) {
                    $product->total_linea_producto = $product->dosis * $product->total_has_bloque;
                }
            }

            if ($product->precio_unitario !== null && $product->total_linea_producto !== null) {
                $product->total_costo_linea = $product->total_linea_producto * $product->precio_unitario;
            } else {
                $product->total_costo_linea = null;
            }
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

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
