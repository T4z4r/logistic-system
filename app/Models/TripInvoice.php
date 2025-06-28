<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripInvoice extends Model
{
    protected $fillable = [
        'invoice_ref',
        'allocation_id',
        'amount',
        'currency_id',
        'real_amount',
        'rate',
        'start_date',
        'due_date',
        'credit_term',
        'vat',
        'status',
        'state',
        'created_by',
        'type',
        'account',
        'note',
        'currency_log_id',
        'credit_amount',
        'payment_status',
        'paid_amount',
        'real_paid_amount'
    ];

    public function allocation()
    {
        return $this->belongsTo(Allocation::class, 'allocation_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
