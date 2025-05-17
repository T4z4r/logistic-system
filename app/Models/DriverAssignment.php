<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverAssignment extends Model
{
    protected $fillable = [
        'driver_id',
        'truck_id',
        'assigned_by',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class, 'truck_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}