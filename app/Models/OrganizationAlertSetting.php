<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationAlertSetting extends Model
{
    protected $fillable = [
        'organization_id',
        'ndvi_enabled',
        'ndmi_enabled',
        'nbr_enabled',
        'ndvi_drop_threshold',
        'ndmi_drop_threshold',
        'nbr_drop_threshold',
        'cloud_max_percent',
        'frequency',
    ];

    protected $casts = [
        'ndvi_enabled' => 'boolean',
        'ndmi_enabled' => 'boolean',
        'nbr_enabled' => 'boolean',
        'ndvi_drop_threshold' => 'decimal:3',
        'ndmi_drop_threshold' => 'decimal:3',
        'nbr_drop_threshold' => 'decimal:3',
        'cloud_max_percent' => 'integer',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
