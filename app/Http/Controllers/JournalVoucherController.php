<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\VoucherType;
use App\Models\Ledger;
use App\Models\CostCenter;
use App\Models\VoucherEntry;
use Illuminate\Http\Request;

class JournalVoucherController extends Controller
{
    public function index()
    {
        $voucherType = VoucherType::where('name', 'Journal')->where('company_id', auth()->user()->company_id)->first();
        $vouchers = Voucher::where('company_id', auth()->user()->company_id)
            ->where('voucher_type_id', $voucherType->id)
            ->get();
        $ledgers = Ledger::where('company_id', auth()->user()->company_id)->get();
        $costCenters = CostCenter::where('company_id', auth()->user()->company_id)->get();
        return view('vouchers.journal.index', compact('vouchers', 'ledgers', 'costCenters', 'voucherType'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'narration' => 'nullable|string',
            'entries' => 'required|array|min:2',
            'entries.*.ledger_id' => 'required|exists:ledgers,id',
            'entries.*.amount' => 'required|numeric|min:0.01',
            'entries.*.type' => 'required|in:debit,credit',
            'entries.*.cost_center_id' => 'nullable|exists:cost_centers,id',
        ]);

        // Validate debit equals credit
        $totalDebit = array_sum(array_column(array_filter($validated['entries'], fn($entry) => $entry['type'] === 'debit'), 'amount'));
        $totalCredit = array_sum(array_column(array_filter($validated['entries'], fn($entry) => $entry['type'] === 'credit'), 'amount'));
        if ($totalDebit != $totalCredit) {
            return back()->withErrors(['entries' => 'Total debit must equal total credit.']);
        }

        $voucherType = VoucherType::where('name', 'Journal')->where('company_id', auth()->user()->company_id)->firstOrFail();
        $voucherNumber = $this->generateVoucherNumber($voucherType);

        $voucher = Voucher::create([
            'company_id' => auth()->user()->company_id,
            'voucher_type_id' => $voucherType->id,
            'voucher_number' => $voucherNumber,
            'date' => $validated['date'],
            'narration' => $validated['narration'],
            'amount' => $totalDebit,
            'currency_id' => auth()->user()->company->currency_id,
        ]);

        // Create voucher entries
        foreach ($validated['entries'] as $entry) {
            VoucherEntry::create([
                'voucher_id' => $voucher->id,
                'ledger_id' => $entry['ledger_id'],
                'amount' => $entry['amount'],
                'type' => $entry['type'],
                'cost_center_id' => $entry['cost_center_id'],
            ]);
        }

        return redirect()->route('vouchers.journal.index')->with('success', 'Journal voucher created successfully.');
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $validated = $request->validate([
            'date' => 'required|date',
            'narration' => 'nullable|string',
            'entries' => 'required|array|min:2',
            'entries.*.ledger_id' => 'required|exists:ledgers,id',
            'entries.*.amount' => 'required|numeric|min:0.01',
            'entries.*.type' => 'required|in:debit,credit',
            'entries.*.cost_center_id' => 'nullable|exists:cost_centers,id',
        ]);

        // Validate debit equals credit
        $totalDebit = array_sum(array_column(array_filter($validated['entries'], fn($entry) => $entry['type'] === 'debit'), 'amount'));
        $totalCredit = array_sum(array_column(array_filter($validated['entries'], fn($entry) => $entry['type'] === 'credit'), 'amount'));
        if ($totalDebit != $totalCredit) {
            return back()->withErrors(['entries' => 'Total debit must equal total credit.']);
        }

        $voucher->update([
            'date' => $validated['date'],
            'narration' => $validated['narration'],
            'amount' => $totalDebit,
        ]);

        $voucher->entries()->delete();
        foreach ($validated['entries'] as $entry) {
            VoucherEntry::create([
                'voucher_id' => $voucher->id,
                'ledger_id' => $entry['ledger_id'],
                'amount' => $entry['amount'],
                'type' => $entry['type'],
                'cost_center_id' => $entry['cost_center_id'],
            ]);
        }

        return redirect()->route('vouchers.journal.index')->with('success', 'Journal voucher updated successfully.');
    }

    public function destroy($id)
    {
        $voucher = Voucher::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $voucher->delete();
        return redirect()->route('vouchers.journal.index')->with('success', 'Journal voucher deleted successfully.');
    }

    private function generateVoucherNumber($voucherType)
    {
        $lastVoucher = Voucher::where('voucher_type_id', $voucherType->id)
            ->where('company_id', auth()->user()->company_id)
            ->latest()
            ->first();
        $number = $lastVoucher ? (int) str_replace($voucherType->prefix, '', $lastVoucher->voucher_number) + 1 : 1;
        return $voucherType->prefix . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}

