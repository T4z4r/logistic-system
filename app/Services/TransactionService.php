<?php

namespace App\Services;

use App\Models\CurrencyLog;
use Illuminate\Support\Facades\DB;
use App\Models\Account\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Models\Account\ProcessLedger;
use App\Models\Account\ProcessLedgerMapper;

class TransactionService
{

    public function createSingleTransaction(array $data): Transaction
    {
        $transaction = new Transaction();
        $transaction->ledger_id = $data['ledger_id'];
        $transaction->driver_id = $data['driver_id'] ?? null;
        $transaction->amount = $data['amount'];
        $transaction->description = $data['description'];
        $transaction->type = $data['type'];
        $transaction->process = $data['process'];
        $transaction->category = $data['category'];
        $transaction->process_id = $data['process_id'];
        $transaction->created_by = Auth::user()->id;
        $transaction->currency_id = $data['currency_id'];
        $transaction->currency_log_id = $data['currency_log_id'];
        $transaction->rate = $data['rate'];
        $transaction->save();

        return $transaction;
    }

    public function transaction(array $request)
    {
        $process = ProcessLedger::where('name', $request["process_name"])->first();
        $mapper = ProcessLedgerMapper::where('process_id', $process->id)->first();

        $debitTranscation = new Transaction();
        $debitTranscation->ledger_id = $mapper->ledger_debit_id;
        $debitTranscation->amount = $request["amount"];
        $debitTranscation->description = $request["description"];
        $debitTranscation->process = $request["process"];
        $debitTranscation->process_id = $request["process_id"];
        $debitTranscation->type = 2;
        $debitTranscation->created_by = Auth::user()->id;
        $debitTranscation->save();

        $creditTranscation = new Transaction();
        $creditTranscation->ledger_id = $mapper->ledger_credit_id;
        $creditTranscation->amount = $request["amount"];
        $creditTranscation->description = $request["description"];
        $creditTranscation->process = $request["process"];
        $creditTranscation->process_id = $request["process_id"];
        $creditTranscation->type = 1;
        $creditTranscation->created_by = Auth::user()->id;
        $creditTranscation->save();

        if ($mapper->vat_id) {
            $vatTransaction = new Transaction();
            $vatTransaction->ledger_id = $mapper->credit_id;
            $vatTransaction->amount = $request["amount"];
            $vatTransaction->description = $request["description"];
            $vatTransaction->process = $request["process"];
            $vatTransaction->process_id = $request["process_id"];
            $vatTransaction->type = 2;
            $vatTransaction->save();
        }

        return $debitTranscation;
    }


    public function transactionV2(array $request)
    {
        $process = ProcessLedger::where('name', $request["process_name"])->first();
        $mapper = ProcessLedgerMapper::where('process_id', $process->id)->first();

        $creditTranscation = new Transaction();
        $creditTranscation->ledger_id = $mapper->credit_id;
        $creditTranscation->amount = $request["amount"];
        $creditTranscation->description = $request["description"];
        $creditTranscation->process = $request["process"];
        $creditTranscation->process_id = $request["process_id"];
        $creditTranscation->type = 1;
        $creditTranscation->created_by = Auth::user()->id;
        $creditTranscation->save();

        $debitTranscation = new Transaction();
        $debitTranscation->ledger_id = $mapper->debit_id;
        $debitTranscation->amount = $request["amount"];
        $debitTranscation->description = $request["description"];
        $debitTranscation->process = $request["process"];
        $debitTranscation->process_id = $request["process_id"];
        $debitTranscation->type = 2;
        $debitTranscation->created_by = Auth::user()->id;
        $debitTranscation->save();

        if ($mapper->vat_id) {
            $vatTransaction = new Transaction();
            $vatTransaction->ledger_id = $mapper->credit_id;
            $vatTransaction->amount = $request["amount"];
            $vatTransaction->description = $request["description"];
            $vatTransaction->type = 2;
            $vatTransaction->save();
        }

        return $debitTranscation;
    }

    public function transactionv3(array $request)
    {
        $process = ProcessLedger::where('name', $request["process_name"])->first();
        $mapper = ProcessLedgerMapper::where('process_id', $process->id)->first();

        // For Currency Log
        $currencyLog = CurrencyLog::latest()->first();
        if ($currencyLog == null) {
            // DB::rollBack();
            return back()->with('error', 'Sorry,There is no any currency Log !');
        }


        $debitTranscation = new Transaction();
        $debitTranscation->ledger_id = $mapper->ledger_debit_id;
        $debitTranscation->amount = $request["tzs_amount"];
        $debitTranscation->description = $request["description"];
        $debitTranscation->process = $request["process"];
        $debitTranscation->process_id = $request["process_id"];
        $debitTranscation->category = $request["category"];
        $debitTranscation->type = 2;
        $debitTranscation->created_by = Auth::user()->id;
        $debitTranscation->currency_log_id = $currencyLog->id;
        $debitTranscation->corridor_amount = ($request['tzs_amount'] / $request['corridor_rate']);
        $debitTranscation->corridor_rate = $request['corridor_rate'];
        $debitTranscation->save();

        $creditTranscation = new Transaction();
        $creditTranscation->ledger_id = $request["credit_account_id"];
        $creditTranscation->amount = $request["tzs_amount"] + $request["tzs_transaction_charges"] + $request["tzs_service_charges"] + $request["tzs_vat_charges"];
        $creditTranscation->description = $request["description"];
        $creditTranscation->process = $request["process"];
        $creditTranscation->category = $request["category"];
        $creditTranscation->process_id = $request["process_id"];
        $creditTranscation->type = 1;
        $creditTranscation->created_by = Auth::user()->id;
        $creditTranscation->currency_log_id = $currencyLog->id;
        $creditTranscation->corridor_amount = $request['corridor_amount'];
        $creditTranscation->corridor_rate = $request['corridor_rate'];
        $creditTranscation->save();

        // if ($mapper->vat_id) {
        //     $vatTransaction = new Transaction();
        //     $vatTransaction->ledger_id = $mapper->credit_id;
        //     $vatTransaction->amount = $request["amount"];
        //     $vatTransaction->description =  $request["description"];
        //     $vatTransaction->process =  $request["process"];
        //     $vatTransaction->process_id =  $request["process_id"];
        //     $vatTransaction->category =  $request["category"];
        //     $vatTransaction->type =  2;
        //     $vatTransaction->currency_log_id=$currencyLog->id;
        //     $vatTransaction->save();
        // }

        return 1;
    }


    public function reverseTransactionv3(array $request)
    {
        // Retrieve the process ledger and its mapper
        $process = ProcessLedger::where('name', $request["process_name"])->first();
        $mapper = ProcessLedgerMapper::where('process_id', $process->id)->first();

        // Check for the latest currency log entry
        $currencyLog = CurrencyLog::latest()->first();
        if ($currencyLog === null) {
            return back()->with('error', 'Sorry, there is no available currency log!');
        }

        // Find and delete the original debit transaction
        $originalDebitTransaction = Transaction::where('ledger_id', $mapper->ledger_debit_id)
            ->where('amount', $request["tzs_amount"])
            ->where('process_id', $request["process_id"])
            ->where('type', 2) // Debit type
            ->latest()
            ->first();

        if ($originalDebitTransaction) {
            $originalDebitTransaction->delete();
        }

        // Find and delete the original credit transaction
        $originalCreditTransaction = Transaction::where('ledger_id', $request["credit_account_id"])
            ->where('amount', $request["tzs_amount"] + $request["tzs_transaction_charges"] + $request["tzs_service_charges"] + $request["tzs_vat_charges"])
            ->where('process_id', $request["process_id"])
            ->where('type', 1) // Credit type
            ->latest()
            ->first();

        if ($originalCreditTransaction) {
            $originalCreditTransaction->delete();
        }

        return 1;
    }
}
