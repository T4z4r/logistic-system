<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllocationCost extends Model
{
    protected $fillable = [
        'allocation_id',
        'name',
        'amount',
        'account_code',
        'created_by',
        'route_id',
        'currency_id',
        'rate',
        'real_amount',
        'status',
        'quantity',
        'vat',
        'editable',
        'type',
        'advancable',
        'paid_amount',
        'remaining_amount',
        'payment_status',
        'return',
        'reason',
        'missing_status',
    ];

    protected $casts = [
        'amount' => 'double',
        'rate' => 'double',
        'real_amount' => 'double',
        'quantity' => 'double',
        'paid_amount' => 'double',
        'remaining_amount' => 'double',
        'status' => 'integer',
        'vat' => 'boolean',
        'editable' => 'boolean',
        'advancable' => 'integer',
        'payment_status' => 'integer',
        'return' => 'boolean',
        'missing_status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function allocation()
    {
        return $this->belongsTo(Allocation::class, 'allocation_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
