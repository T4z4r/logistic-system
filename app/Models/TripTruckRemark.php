<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripTruckRemark extends Model
{
    use HasFactory;

    protected $fillable = [
        'truck_id',
        'remark',
        'status',
        'remarked_by',
        'created_by',
    ];

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
