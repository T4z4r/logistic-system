<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripCost extends Model
{
    protected $fillable = [
        'name', 'amount', 'trip_id', 'cost_id', 'status', 'created_by', 'return'
    ];
}
