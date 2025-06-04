<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\VoucherType;
use App\Models\Ledger;
use App\Models\VoucherEntry;
use Illuminate\Http\Request;

class ContraVoucherController extends Controller
{
    public function index()
    {
        $voucherType = VoucherType::where('name', 'Contra')->where('company_id', auth()->user()->company_id)->first();
        $vouchers = Voucher::where('company_id', auth()->user()->company_id)
            ->where('voucher_type_id', $voucherType->id)
            ->get();
        $ledgers = Ledger::where('company_id', auth()->user()->company_id)->get();
        return view('vouchers.contra.index', compact('vouchers', 'ledgers', 'voucherType'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'debit_ledger_id' => 'required|exists:ledgers,id',
            'credit_ledger_id' => 'required|exists:ledgers,id',
            'narration' => 'nullable|string',
        ]);

        $voucherType = VoucherType::where('name', 'Contra')->where('company_id', auth()->user()->company_id)->firstOrFail();
        $voucherNumber = $this->generateVoucherNumber($voucherType);

        $voucher = Voucher::create([
            'company_id' => auth()->user()->company_id,
            'voucher_type_id' => $voucherType->id,
            'voucher_number' => $voucherNumber,
            'date' => $validated['date'],
            'narration' => $validated['narration'],
            'amount' => $validated['amount'],
            'currency_id' => auth()->user()->company->currency_id,
        ]);

        // Debit one cash/bank ledger
        VoucherEntry::create([
            'voucher_id' => $voucher->id,
            'ledger_id' => $validated['debit_ledger_id'],
            'amount' => $validated['amount'],
            'type' => 'debit',
        ]);

        // Credit another cash/bank ledger
        VoucherEntry::create([
            'voucher_id' => $voucher->id,
            'ledger_id' => $validated['credit_ledger_id'],
            'amount' => $validated['amount'],
            'type' => 'credit',
        ]);

        return redirect()->route('vouchers.contra.index')->with('success', 'Contra voucher created successfully.');
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'debit_ledger_id' => 'required|exists:ledgers,id',
            'credit_ledger_id' => 'required|exists:ledgers,id',
            'narration' => 'nullable|string',
        ]);

        $voucher->update([
            'date' => $validated['date'],
            'narration' => $validated['narration'],
            'amount' => $validated['amount'],
        ]);

        $voucher->entries()->delete();
        VoucherEntry::create([
            'voucher_id' => $voucher->id,
            'ledger_id' => $validated['debit_ledger_id'],
            'amount' => $validated['amount'],
            'type' => 'debit',
        ]);
        VoucherEntry::create([
            'voucher_id' => $voucher->id,
            'ledger_id' => $validated['credit_ledger_id'],
            'amount' => $validated['amount'],
            'type' => 'credit',
        ]);

        return redirect()->route('vouchers.contra.index')->with('success', 'Contra voucher updated successfully.');
    }

    public function destroy($id)
    {
        $voucher = Voucher::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $voucher->delete();
        return redirect()->route('vouchers.contra.index')->with('success', 'Contra voucher deleted successfully.');
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
