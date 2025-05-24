<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllocationRemark extends Model
{
    protected $fillable = [
        'allocation_id', 'remark', 'status', 'remarked_by', 'created_by'
    ];

    public function allocation()
    {
        return $this->belongsTo(Allocation::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
