<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\VoucherType;
use App\Models\Ledger;
use App\Models\CostCenter;
use App\Models\VoucherEntry;
use Illuminate\Http\Request;

class PaymentVoucherController extends Controller
{
    public function index()
    {
        $voucherType = VoucherType::where('name', 'Payment')->where('company_id', auth()->user()->company_id)->first();
        $vouchers = Voucher::where('company_id', auth()->user()->company_id)
            ->where('voucher_type_id', $voucherType->id)
            ->get();
        $ledgers = Ledger::where('company_id', auth()->user()->company_id)->get();
        $costCenters = CostCenter::where('company_id', auth()->user()->company_id)->get();
        return view('vouchers.payment.index', compact('vouchers', 'ledgers', 'costCenters', 'voucherType'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'party_ledger_id' => 'required|exists:ledgers,id',
            'cash_bank_ledger_id' => 'required|exists:ledgers,id',
            'narration' => 'nullable|string',
            'cost_center_id' => 'nullable|exists:cost_centers,id',
        ]);

        $voucherType = VoucherType::where('name', 'Payment')->where('company_id', auth()->user()->company_id)->firstOrFail();
        $voucherNumber = $this->generateVoucherNumber($voucherType);

        $voucher = Voucher::create([
            'company_id' => auth()->user()->company_id,
            'voucher_type_id' => $voucherType->id,
            'voucher_number' => $voucherNumber,
            'date' => $validated['date'],
            'narration' => $validated['narration'],
            'amount' => $validated['amount'],
            'currency_id' => auth()->user()->company->currency_id??1,
        ]);

        // Debit party ledger
        VoucherEntry::create([
            'voucher_id' => $voucher->id,
            'ledger_id' => $validated['party_ledger_id'],
            'amount' => $validated['amount'],
            'type' => 'debit',
            'cost_center_id' => $validated['cost_center_id'],
        ]);

        // Credit cash/bank ledger
        VoucherEntry::create([
            'voucher_id' => $voucher->id,
            'ledger_id' => $validated['cash_bank_ledger_id'],
            'amount' => $validated['amount'],
            'type' => 'credit',
        ]);

        return redirect()->route('vouchers.payment.index')->with('success', 'Payment voucher created successfully.');
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'party_ledger_id' => 'required|exists:ledgers,id',
            'cash_bank_ledger_id' => 'required|exists:ledgers,id',
            'narration' => 'nullable|string',
            'cost_center_id' => 'nullable|exists:cost_centers,id',
        ]);

        $voucher->update([
            'date' => $validated['date'],
            'narration' => $validated['narration'],
            'amount' => $validated['amount'],
        ]);

        $voucher->entries()->delete();
        VoucherEntry::create([
            'voucher_id' => $voucher->id,
            'ledger_id' => $validated['party_ledger_id'],
            'amount' => $validated['amount'],
            'type' => 'debit',
            'cost_center_id' => $validated['cost_center_id'],
        ]);
        VoucherEntry::create([
            'voucher_id' => $voucher->id,
            'ledger_id' => $validated['cash_bank_ledger_id'],
            'amount' => $validated['amount'],
            'type' => 'credit',
        ]);

        return redirect()->route('vouchers.payment.index')->with('success', 'Payment voucher updated successfully.');
    }

    public function destroy($id)
    {
        $voucher = Voucher::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $voucher->delete();
        return redirect()->route('vouchers.payment.index')->with('success', 'Payment voucher deleted successfully.');
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

