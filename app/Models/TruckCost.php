<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TruckCost extends Model
{
    protected $fillable = [
        'allocation_id', 'truck_id', 'name', 'amount', 'account_code',
        'route_id', 'currency_id', 'rate', 'real_amount', 'status',
        'created_by', 'quantity', 'editable', 'type', 'mobilization',
        'advancable', 'return', 'transaction_charges', 'service_charges',
        'vat_charges', 'paid_amount', 'real_paid_amount', 'payment_status',
        'reason', 'missing_status'
    ];
}
