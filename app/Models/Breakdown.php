<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Breakdown extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'truck_id',
        'trip_id',
        'breakdown_category_id',
        'breakdown_item_id',
        'added_by_id',
        'description',
        'cost',
        'type_of_breakdown',
        'status',
        'brakedown_date',
        'state',
        'currency_id',
        'new_cost',
        'is_paid',
        'pay_type',
        'payment_date',
        'rate',
        'real_amount',
        'new_cost_currency_id',
        'breakdown_level',
        'payment_level',
        'code',
        'location',
        'breakdown_type',
        'closed_date',
        'closed_by_id',
        'created_by',
        'workshop_status',
    ];

    protected $casts = [
        'brakedown_date' => 'datetime',
        'payment_date' => 'datetime',
        'closed_date' => 'datetime',
        'is_paid' => 'boolean',
        'workshop_status' => 'boolean',
    ];


       public function truck(): BelongsTo
    {
        return $this->belongsTo(Truck::class, "truck_id");
    }

    // public function breakdown_category(): BelongsTo
    // {
    //     return $this->belongsTo(BreakdownCategory::class, "breakdown_category_id");
    // }
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, "currency_id");
    }

    public function new_cost_currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, "new_cost_currency_id");
    }

    // public function breakdown_item(): BelongsTo
    // {
    //     return $this->belongsTo(BreakdownItem::class, "breakdown_item_id");
    // }

    // public function rescue_truck(){
    //     return $this->hasOne(TruckRescue::class , 'breakdown_id');
    // }

    // public function workshop_request()
    // {
    //     return $this->hasOne(WorkshopRequest::class, 'breakdown_id', 'id');
    // }

    // public function workshop_request_item()
    // {
    //     return $this->hasMany(WorkshopRequestItem::class, 'breakdown_id', 'id');
    // }

    // public function personnel_asignments()
    // {
    //     return $this->hasMany(PersonnelAssignment::class, 'breakdown_id', 'id');
    // }

    // public function expenses()
    // {
    //     return $this->hasMany(BreakdownExpense::class, 'breakdown_id', 'id');
    // }

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class, "trip_id");
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, "driver_id");
    }

    public function added_by(): BelongsTo
    {
        return $this->belongsTo(User::class, "added_by_id");
    }

    public function closed_by(): BelongsTo
    {
        return $this->belongsTo(User::class, "closed_by_id");
    }

    public function approval(): BelongsTo
    {
        return $this->belongsTo(Approval::class, "approval_id");
    }
}
