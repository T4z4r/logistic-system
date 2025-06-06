<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Truck extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'purchase_date',
        'plate_number',
        'body_type',
        'truck_type',
        'fuel_type',
        'fuel_capacity',
        'trailer_connection',
        'trailer_capacity',
        'transmission',
        'mileage',
        'vehicle_model',
        'manufacturer',
        'year',
        'color',
        'engine_number',
        'engine_capacity',
        'gross_weight',
        'location',
        'status',
        'added_by',
        'amount',
        'capacity',
    ];

    protected $casts = [
        'status' => 'boolean',
        'purchase_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function type()
    {
        return $this->belongsTo(TruckType::class, 'truck_type', 'id');
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function assignments()
    {
        return $this->hasMany(DriverAssignment::class, 'truck_id');
    }

    public function trailers()
    {
        return $this->hasMany(TrailerAssignment::class, 'trailer_id');
    }
}
