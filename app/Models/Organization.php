<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subscription_plan',
        'status',
        'plan_id',
        'trial_start_date',
        'trial_end_date',
        'paid_plan_start_date',
        'paid_plan_end_date',
        'billing_name',
        'billing_address',
        'billing_cuit',
        'billing_iva',
    ];

    protected $casts = [
        'trial_start_date' => 'date',
        'trial_end_date' => 'date',
        'paid_plan_start_date' => 'date',
        'paid_plan_end_date' => 'date',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function farms()
    {
        return $this->hasMany(Farm::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'organization_product')
            ->withTimestamps();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function alertSettings()
    {
        return $this->hasOne(OrganizationAlertSetting::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
