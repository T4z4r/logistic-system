<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ServicePurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_purchase_id',
        'service_name',
        'price',
        'quantity',
        'tax_rate',
        'total',
        'status',
        'allocation_id',
        'truck_cost_id',
        'allocation_cost_id',
        'retirement_id',
        'discount_amount'
    ];

    public function servicePurchase()
    {
        return $this->belongsTo(ServicePurchase::class);
    }
}