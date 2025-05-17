<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trailer extends Model
{
    protected $fillable = [
        'plate_number',
        'purchase_date',
        'amount',
        'capacity',
        'manufacturer',
        'length',
        'trailer_type',
        'status',
        'added_by',
    ];

    protected $casts = [
        'status' => 'boolean',
        'purchase_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }


    public function assignments()
    {
        return $this->hasMany(TrailerAssignment::class, 'trailer_id');
    }
}
