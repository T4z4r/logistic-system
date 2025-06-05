<?php

namespace App\Http\Controllers;
// use App\Models\Ledger;
use App\Models\Ledger;
use App\Models\VoucherEntry;
use Illuminate\Http\Request;


class ReportsController extends Controller
{
    public function trialBalance(Request $request)
    {
        $companyId = auth()->user()->company_id;
        $startDate = $request->input('start_date', now()->subYear()->startOfDay());
        $endDate = $request->input('end_date', now()->endOfDay());

        $ledgers = Ledger::where('company_id', $companyId)->with(['entries' => function ($query) use ($startDate, $endDate) {
            $query->whereHas('voucher', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            });
        }])->get();

        $trialBalance = $ledgers->map(function ($ledger) {
            $openingBalance = $ledger->opening_balance ?? 0;
            $debit = $ledger->entries->where('type', 'debit')->sum('amount');
            $credit = $ledger->entries->where('type', 'credit')->sum('amount');
            $closingBalance = $openingBalance + $debit - $credit;

            return [
                'ledger' => $ledger->name,
                'opening_balance' => $openingBalance,
                'debit' => $debit,
                'credit' => $credit,
                'closing_balance' => $closingBalance,
            ];
        })->filter(function ($item) {
            return $item['opening_balance'] != 0 || $item['debit'] != 0 || $item['credit'] != 0 || $item['closing_balance'] != 0;
        });

        return view('accounting-reports.trial-balance', compact('trialBalance', 'startDate', 'endDate'));
    }

        public function profitLoss(Request $request)
    {
        $companyId = auth()->user()->company_id;
        $startDate = $request->input('start_date', now()->subYear()->startOfDay());
        $endDate = $request->input('end_date', now()->endOfDay());

        $incomeLedgers = Ledger::where('company_id', $companyId)
            ->whereHas('group', function ($query) {
                $query->where('type', 'income');
            })->with(['entries' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('voucher', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate]);
                });
            }])->get();

        $expenseLedgers = Ledger::where('company_id', $companyId)
            ->whereHas('group', function ($query) {
                $query->where('type', 'expense');
            })->with(['entries' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('voucher', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate]);
                });
            }])->get();

        $income = $incomeLedgers->sum(function ($ledger) {
            return $ledger->entries->where('type', 'credit')->sum('amount') - $ledger->entries->where('type', 'debit')->sum('amount');
        });

        $expense = $expenseLedgers->sum(function ($ledger) {
            return $ledger->entries->where('type', 'debit')->sum('amount') - $ledger->entries->where('type', 'credit')->sum('amount');
        });

        $netProfit = $income - $expense;

        return view('accounting-reports.profit-loss', compact('incomeLedgers', 'expenseLedgers', 'income', 'expense', 'netProfit', 'startDate', 'endDate'));
    }

        public function balanceSheet(Request $request)
    {
        $companyId = auth()->user()->company_id;
        $endDate = $request->input('end_date', now()->endOfDay());

        $assetLedgers = Ledger::where('company_id', $companyId)
            ->whereHas('group', function ($query) {
                $query->where('type', 'asset');
            })->with(['entries' => function ($query) use ($endDate) {
                $query->whereHas('voucher', function ($q) use ($endDate) {
                    $q->where('date', '<=', $endDate);
                });
            }])->get();

        $liabilityLedgers = Ledger::where('company_id', $companyId)
            ->whereHas('group', function ($query) {
                $query->where('type', 'liability');
            })->with(['entries' => function ($query) use ($endDate) {
                $query->whereHas('voucher', function ($q) use ($endDate) {
                    $q->where('date', '<=', $endDate);
                });
            }])->get();

        $assets = $assetLedgers->map(function ($ledger) {
            $balance = $ledger->opening_balance + $ledger->entries->where('type', 'debit')->sum('amount') - $ledger->entries->where('type', 'credit')->sum('amount');
            return ['ledger' => $ledger->name, 'balance' => $balance];
        })->filter(function ($item) {
            return $item['balance'] != 0;
        });

        $liabilities = $liabilityLedgers->map(function ($ledger) {
            $balance = $ledger->opening_balance + $ledger->entries->where('type', 'credit')->sum('amount') - $ledger->entries->where('type', 'debit')->sum('amount');
            return ['ledger' => $ledger->name, 'balance' => $balance];
        })->filter(function ($item) {
            return $item['balance'] != 0;
        });

        // Calculate net profit/loss for capital
        $incomeLedgers = Ledger::where('company_id', $companyId)
            ->whereHas('group', function ($query) {
                $query->where('type', 'income');
            })->with(['entries' => function ($query) use ($endDate) {
                $query->whereHas('voucher', function ($q) use ($endDate) {
                    $q->where('date', '<=', $endDate);
                });
            }])->get();

        $expenseLedgers = Ledger::where('company_id', $companyId)
            ->whereHas('group', function ($query) {
                $query->where('type', 'expense');
            })->with(['entries' => function ($query) use ($endDate) {
                $query->whereHas('voucher', function ($q) use ($endDate) {
                    $q->where('date', '<=', $endDate);
                });
            }])->get();

        $income = $incomeLedgers->sum(function ($ledger) {
            return $ledger->entries->where('type', 'credit')->sum('amount') - $ledger->entries->where('type', 'debit')->sum('amount');
        });

        $expense = $expenseLedgers->sum(function ($ledger) {
            return $ledger->entries->where('type', 'debit')->sum('amount') - $ledger->entries->where('type', 'credit')->sum('amount');
        });

        $netProfit = $income - $expense;

        return view('accounting-reports.balance-sheet', compact('assets', 'liabilities', 'netProfit', 'endDate'));
    }


        public function ledger(Request $request)
    {
        $companyId = auth()->user()->company_id;
        $ledgers = Ledger::where('company_id', $companyId)->get();
        $selectedLedgerId = $request->input('ledger_id');
        $startDate = $request->input('start_date', now()->subYear()->startOfDay());
        $endDate = $request->input('end_date', now()->endOfDay());

        $transactions = collect();
        $ledger = null;

        if ($selectedLedgerId) {
            $ledger = Ledger::where('id', $selectedLedgerId)->where('company_id', $companyId)->first();
            if ($ledger) {
                $transactions = VoucherEntry::where('ledger_id', $selectedLedgerId)
                    ->whereHas('voucher', function ($query) use ($startDate, $endDate, $companyId) {
                        $query->where('company_id', $companyId)
                            ->whereBetween('date', [$startDate, $endDate]);
                    })->with('voucher')->get();

                $openingBalance = $ledger->opening_balance;
                $runningBalance = $openingBalance;

                $transactions = $transactions->map(function ($entry) use (&$runningBalance) {
                    $amount = $entry->type === 'debit' ? $entry->amount : -$entry->amount;
                    $runningBalance += $amount;
                    return [
                        'date' => $entry->voucher->date,
                        'voucher_number' => $entry->voucher->voucher_number,
                        'narration' => $entry->voucher->narration,
                        'debit' => $entry->type === 'debit' ? $entry->amount : 0,
                        'credit' => $entry->type === 'credit' ? $entry->amount : 0,
                        'balance' => $runningBalance,
                    ];
                });
            }
        }

        return view('accounting-reports.ledger', compact('ledgers', 'selectedLedgerId', 'transactions', 'ledger', 'startDate', 'endDate'));
    }

        public function cashBook(Request $request)
    {
        $companyId = auth()->user()->company_id;
        $ledgers = Ledger::where('company_id', $companyId)
            ->whereHas('group', function ($query) {
                $query->whereIn('name', ['Cash-in-hand', 'Bank Accounts']);
            })->get();
        $selectedLedgerId = $request->input('ledger_id');
        $startDate = $request->input('start_date', now()->subMonth()->startOfDay());
        $endDate = $request->input('end_date', now()->endOfDay());

        $transactions = collect();
        $ledger = null;

        if ($selectedLedgerId) {
            $ledger = Ledger::where('id', $selectedLedgerId)->where('company_id', $companyId)->first();
            if ($ledger) {
                $transactions = VoucherEntry::where('ledger_id', $selectedLedgerId)
                    ->whereHas('voucher', function ($query) use ($startDate, $endDate, $companyId) {
                        $query->where('company_id', $companyId)
                            ->whereBetween('date', [$startDate, $endDate]);
                    })->with('voucher')->get();

                $openingBalance = $ledger->opening_balance;
                $runningBalance = $openingBalance;

                $transactions = $transactions->map(function ($entry) use (&$runningBalance) {
                    $amount = $entry->type === 'debit' ? $entry->amount : -$entry->amount;
                    $runningBalance += $amount;
                    return [
                        'date' => $entry->voucher->date,
                        'voucher_number' => $entry->voucher->voucher_number,
                        'narration' => $entry->voucher->narration,
                        'debit' => $entry->type === 'debit' ? $entry->amount : 0,
                        'credit' => $entry->type === 'credit' ? $entry->amount : 0,
                        'balance' => $runningBalance,
                    ];
                });
            }
        }

        return view('accounting-reports.cash-book', compact('ledgers', 'selectedLedgerId', 'transactions', 'ledger', 'startDate', 'endDate'));
    }


}
