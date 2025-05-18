<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyLog extends Model
{
    protected $fillable = [
        'created_date',
        'created_by',
    ];

    protected $casts = [
        'created_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(CurrencyLogItem::class, 'currency_log_id');
    }
}