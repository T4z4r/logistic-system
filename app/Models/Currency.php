<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        'name',
        'symbol',
        'currency',
        'rate',
        'status',
        'created_by',
        'code',
        'value',
        'corridor_rate',
    ];

    protected $casts = [
        'status' => 'boolean',
        'value' => 'double',
        'corridor_rate' => 'double',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function createdBy()
    {
    return $this->belongsTo(User::class, 'created_by');
    }

    public function currencyLogItems()
    {
        return $this->hasMany(CurrencyLogItem::class, 'currency_id');
    }

    public function allocationCosts()
    {
        return $this->hasMany(AllocationCost::class, 'currency_id');
    }
}
