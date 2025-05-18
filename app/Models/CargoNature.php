<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CargoNature extends Model
{
    protected $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
