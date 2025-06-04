<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_id', 'ledger_id', 'amount', 'type', 'cost_center_id'
    ];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function ledger()
    {
        return $this->belongsTo(Ledger::class);
    }

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class);
    }
}

