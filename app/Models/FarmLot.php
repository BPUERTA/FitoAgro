<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmLot extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'name',
        'has',
        'is_agricola',
        'is_ganadera',
        'is_otro',
        'vigia_alerts_enabled',
        'polygon_coordinates',
    ];

    protected $casts = [
        'has' => 'decimal:2',
        'is_agricola' => 'boolean',
        'is_ganadera' => 'boolean',
        'is_otro' => 'boolean',
        'vigia_alerts_enabled' => 'boolean',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
