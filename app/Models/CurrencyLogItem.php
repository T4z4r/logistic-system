<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyLogItem extends Model
{
    protected $fillable = [
        'currency_log_id',
        'currency_id',
        'rate',
        'created_by',
        'corridor_rate',
    ];

    protected $casts = [
        'rate' => 'double',
        'corridor_rate' => 'double',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function currencyLog()
    {
        return $this->belongsTo(CurrencyLog::class, 'currency_log_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}