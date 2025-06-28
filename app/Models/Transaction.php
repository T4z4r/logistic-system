<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ledger_id',
        'amount',
        'description',
        'type',
        'status',
        'process',
        'process_id',
        'driver_id',
        'created_by',
        'category',
        'currency_id',
        'rate',
        'currency_value',
        'currency_log_id',
        'corridor_rate',
        'corridor_amount'
    ];

    public function ledger()
    {
        return $this->belongsTo(Ledger::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
