<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TruckManagerAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'manager_id', 'truck_id', 'assigned_by', 'status',
    ];
}
