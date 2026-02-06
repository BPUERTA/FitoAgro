<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'amount',
        'currency',
        'payment_date',
        'status',
        'payment_method',
        'notes'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Relación con la organización
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
