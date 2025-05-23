<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref_no',
        'allocation_id',
        'initiated_date',
        'total_payment',
        'status',
        'state',
        'created_by',
        'type',
        'approval_status',
        'completion_approval_status',
        'invoiced_income',
    ];

    protected $casts = [
        'initiated_date' => 'date',
        'status' => 'boolean',
    ];
}