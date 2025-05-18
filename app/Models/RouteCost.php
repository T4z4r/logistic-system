<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteCost extends Model
{
    protected $fillable = [
        'name',
        'amount',
        'account_code',
        'created_by',
        'cost_id',
        'route_id',
        'currency_id',
        'rate',
        'real_amount',
        'quantity',
        'vat',
        'editable',
        'type',
        'advancable',
        'return',
        'status',
    ];

    protected $casts = [
        'vat' => 'boolean',
        'editable' => 'boolean',
        'return' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function cost()
    {
        return $this->belongsTo(CommonCost::class, 'cost_id'); // Adjust to FuelCost if needed
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