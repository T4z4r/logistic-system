<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobilizationCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'amount', 'quantity', 'account_code', 'created_by',
        'cost_id', 'route_id', 'currency_id', 'rate', 'real_amount',
        'vat', 'editable', 'type', 'advancable', 'return', 'status'
    ];
}
