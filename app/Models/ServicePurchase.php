<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'service_purchase_prefix',
        'service_purchase_order_no',
        'subject',
        'tax_amount',
        'currency_symbol',
        'currency_rate',
        'subtotal',
        'payment_amount',
        'total',
        'description',
        'order_status',
        'state',
        'service_purchase_date',
        'due_date',
        'workshop_request_id',
        'status',
        'purchase_type',
        'payment_level_status',
        'procurement_level_status',
        'created_by',
        'tag',
        'offbudget_id',
        'admin_id',
        'retirement_id',
        'transaction_charges',
        'discount_amount',
        'old_status'
    ];

    public function items()
    {
        return $this->hasMany(ServicePurchaseItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
