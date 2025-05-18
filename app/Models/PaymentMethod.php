<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'ledger_id',
        'created_by',
        'status',
        'currency_id',
        'account_number_usd',
        'account_number_tzs',
        'branch_name',
        'bank_name',
        'swift_code',
        'branch_code',
    ];

    protected $casts = [
        'status' => 'integer',
        'currency_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function ledger()
    {
        return $this->belongsTo(Ledger::class, 'ledger_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}