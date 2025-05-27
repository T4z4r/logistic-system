<?php

namespace App\Models;

use App\Models\Allocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trip extends Model
{
    use HasFactory;
    // for relationship
    public function allocation()
    {
        return $this->belongsTo(Allocation::class, 'allocation_id', 'id');
    }

    // public function final_inspection()
    // {
    //     return $this->hasOne(FinalInspection::class, 'trip_id', 'id');
    // }

    // public function brakedown()
    // {
    //     return $this->hasOne(Breakdown::class, 'trip_id', 'id');
    // }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
