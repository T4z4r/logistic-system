<?php

namespace App\Helpers;

use App\Models\SystemLog;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class SystemLogHelper
{


    function postTransaction($creditLedger, $debitLedger, $amount, $offBudget,$process,$process_id,$description) {
        // Start of Transaction
        $creditTransaction = new Transaction();
        $creditTransaction->ledger_id = $creditLedger;
        $creditTransaction->amount = $amount;
        $creditTransaction->description =$description;
        $creditTransaction->type = 1;
        $creditTransaction->process = $process;
        $creditTransaction->process_id = $offBudget->id;
        $creditTransaction->created_by = Auth::user()->id;
        $creditTransaction->save();

        $debitTransaction = new Transaction();
        $debitTransaction->ledger_id = $debitLedger;
        $debitTransaction->amount = $amount;
        $debitTransaction->description = $description;
        $debitTransaction->type = 2;
        $debitTransaction->process = $process;
        $debitTransaction->process_id = $process_id;
        $debitTransaction->created_by = Auth::user()->id;
        $debitTransaction->save();
    }
}
