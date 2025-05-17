<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrailerAssignment extends Model
{
    protected $fillable = [
        'trailer_id',
        'truck_id',
        'assigned_by',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function trailer()
    {
        return $this->belongsTo(Trailer::class, 'trailer_id');
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