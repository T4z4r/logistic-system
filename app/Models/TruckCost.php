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


      public function ledger()
    {
        return $this->belongsTo(LedgerAccount::class, 'account_code','code');
    }
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by','id');
    }

    public function allocation()
    {
        return $this->belongsTo(TruckAllocation::class, 'allocation_id','id');
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class, 'truck_id','id');
    }


    public function route_cost()
    {
        return $this->belongsTo(RouteCost::class, 'name','name');
    }

    public function mobilization_cost()
    {
        return $this->belongsTo(MobilizationCost::class, 'name','name');
    }
}
