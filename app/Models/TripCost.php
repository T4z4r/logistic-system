<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TripCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'amount', 'trip_id', 'cost_id', 'status', 'created_by', 'return'
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function cost()
    {
        return $this->belongsTo(AllocationCost::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
