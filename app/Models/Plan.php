<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'monthly_price',
        'annual_discount',
        'yearly_price',
        'currency',
        'max_users',
        'max_work_orders',
        'max_farms',
        'max_clients',
        'clients_can_create_users',
        'status',
        'published',
        'trial_days',
        'features',
    ];

    protected $casts = [
        'features' => 'array',
        'status' => 'boolean',
        'published' => 'boolean',
        'monthly_price' => 'decimal:2',
        'annual_discount' => 'decimal:2',
        'yearly_price' => 'decimal:2',
        'clients_can_create_users' => 'boolean',
    ];

    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }
}
