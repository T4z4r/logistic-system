<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $fillable = [
        'name',
        'start_point',
        'destination',
        'estimated_distance',
        'estimated_days',
        'status',
        'created_by',
    ];

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function costs()
    {
        return $this->hasMany(RouteCost::class, 'route_id');
    }
}
