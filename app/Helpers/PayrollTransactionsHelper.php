<?php

namespace App\Helpers;

use App\Models\User;

use App\Models\ErpPayrollLog;
use App\Models\PayrollInputLedger;
use App\Models\Account\Transaction;
use Illuminate\Support\Facades\Auth;


class PayrollTransactionsHelper
{
    public static function logPayrollTransactions($payrollMonth)
    {
        $payrollLogs = ErpPayrollLog::where('payroll_date', $payrollMonth)->get();


        foreach ($payrollLogs as  $payrollLog) {
            $payrollLedgers = PayrollInputLedger::get();

            foreach ($payrollLedgers as $ledger) {

                if ($ledger->status == '1') {
                    $column = $ledger->column_name;
                    $amount = $payrollLog->$column;
                    $employee = User::where('emp_id', $payrollLog->empID)->first();
                    $debitLedger = $ledger->ledger_id;
                    $creditLedger = $ledger->account_id;
                    $process = 'Payroll Transaction';
                    $process_id = $payrollLog->id;
                    $description = $employee->fname . ' ' . $employee->mname . ' ' . $employee->lname . ': Payment for  ' . $ledger->input_name . ' in ' . $payrollMonth . ' payroll';


                    $creditTransaction = new Transaction();
                    $creditTransaction->ledger_id = $creditLedger;
                    $creditTransaction->amount = $amount;
                    $creditTransaction->description = $description;
                    $creditTransaction->type = 1;
                    $creditTransaction->process = $process;
                    $creditTransaction->process_id = $process_id;
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
        }
    }
}
