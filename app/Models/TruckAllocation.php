<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TruckAllocation extends Model
{
    use HasFactory;


    protected $fillable = [
        'allocation_id',
        'truck_id',
        'trailer_id',
        'driver_id',
        'status',
        'total_cost',
        'additional_cost',
        'income',
        'loaded',
        'offloaded',
        'loading_date',
        'offload_date',
        'currency',
        'cost_status',
        'payment_status',
        'created_by',
        'pod',
        'rescue_status',
        'planned',
        'initiation_status',
        'trip_status',
        'usd_income',
        'mobilization',
        'mobilization_route'
    ];
}
