<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    protected $fillable = [
        'ref_no',
        'Customer_id',
        'amount',
        'quantity',
        'cargo',
        'cargo_ref',
        'estimated_pay',
        'cargo_nature',
        'payment_mode',
        'loading_site',
        'offloading_site',
        'clearance',
        'container',
        'container_type',
        'dimensions',
        'payment_currency',
        'rate',
        'real_amount',
        'route_id',
        'start_date',
        'end_date',
        'unit',
        'status',
        'approval_status',
        'type',
        'state',
        'created_by',
        'goingload_id',
        'approver_id',
        'disapprover_id',
        'usd_income',
    ];

    protected $casts = [
        'amount' => 'double',
        'quantity' => 'double',
        'estimated_pay' => 'double',
        'rate' => 'double',
        'real_amount' => 'double',
        'usd_income' => 'double',
        'status' => 'integer',
        'approval_status' => 'integer',
        'type' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function nature()
    {
        return $this->belongsTo(CargoNature::class, 'cargo_nature');
    }

    public function mode()
    {
        return $this->belongsTo(PaymentMode::class, 'payment_mode');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function disapprover()
    {
        return $this->belongsTo(User::class, 'disapprover_id');
    }
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'payment_currency', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function truck_allocations()
    {
        return $this->hasMany(TruckAllocation::class, 'allocation_id', 'id');
    }
}
