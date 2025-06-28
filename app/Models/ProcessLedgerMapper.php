<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessLedgerMapper extends Model
{
    protected $fillable = [
        'process_id',
        'ledger_debit_id',
        'ledger_credit_id',
        'sub_account_debit_id',
        'sub_account_credit_id',
        'vat_id',
        'vat_percentage',
        'status',
        'credit_level',
        'debit_level',
    ];

    public function process()
    {
        return $this->belongsTo(ProcessLedger::class, 'process_id');
    }
}
