<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TruckChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'allocation_id',
        'reason',
        'requested_by',
        'status',
        'approval_status',
    ];

    public function allocation()
    {
        return $this->belongsTo(TruckAllocation::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
