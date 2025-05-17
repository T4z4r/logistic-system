<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuelCost extends Model
{
    protected $fillable = [
        'name',
        'ledger_id',
        'created_by',
        'vat',
        'editable',
    ];

    protected $casts = [
        'vat' => 'boolean',
        'editable' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function ledger()
    {
        return $this->belongsTo(User::class, 'ledger_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
