<?php

// app/Models/Supplier.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name', 'phone', 'email', 'company', 'credit_term', 'address',
        'tin_number', 'vrn_number', 'bank_name', 'bank_account',
        'user_type', 'status', 'created_by', 'balance_ledger', 'region_id',
    ];

    public function region()
    {
        return $this->belongsTo(OrgRegion::class, 'region_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
