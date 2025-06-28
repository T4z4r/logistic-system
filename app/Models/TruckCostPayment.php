<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TruckCostPayment extends Model
{
    protected $fillable = [
        'truck_id',
        'cost_id',
        'paid_by',
        'paid_date',
        'amount',
        'remain',
        'transaction_charges',
        'service_charges',
        'vat_charges',
        'paid_amount',
        'real_paid_amount',
        'payment_status',
        'transfer',
        'reason',
        'station',
        'currency_log_id'
    ];

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }
    public function cost()
    {
        return $this->belongsTo(TruckCost::class, 'cost_id');
    }
    public function payer()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }
}