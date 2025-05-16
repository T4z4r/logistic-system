<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'contact_person',
        'TIN',
        'VRN',
        'phone',
        'address',
        'email',
        'company',
        'abbreviation',
        'created_by',
        'status',
        'credit_term',
    ];

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
