<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\VoucherType;
use App\Models\Ledger;
use App\Models\CostCenter;
use App\Models\StockItem;
use App\Models\Godown;
use App\Models\VoucherEntry;
use App\Models\StockEntry;
use App\Models\GodownStock;
use Illuminate\Http\Request;

class SalesVoucherController extends Controller
{
    public function index()
    {
        $voucherType = VoucherType::where('name', 'Sales')->where('company_id', auth()->user()->company_id)->first();
        $vouchers = Voucher::where('company_id', auth()->user()->company_id)
            ->where('voucher_type_id', $voucherType->id)
            ->get();
        $ledgers = Ledger::where('company_id', auth()->user()->company_id)->get();
        $costCenters = CostCenter::where('company_id', auth()->user()->company_id)->get();
        $stockItems = StockItem::where('company_id', auth()->user()->company_id)->get();
        $godowns = Godown::where('company_id', auth()->user()->company_id)->get();
        return view('vouchers.sales.index', compact('vouchers', 'ledgers', 'costCenters', 'stockItems', 'godowns', 'voucherType'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'party_ledger_id' => 'required|exists:ledgers,id',
            'sales_ledger_id' => 'required|exists:ledgers,id',
            'stock_item_id' => 'required|exists:stock_items,id',
            'godown_id' => 'required|exists:godowns,id',
            'quantity' => 'required|numeric|min:0.01',
            'rate' => 'required|numeric|min:0',
            'narration' => 'nullable|string',
            'cost_center_id' => 'nullable|exists:cost_centers,id',
        ]);

        $godownStock = GodownStock::where('stock_item_id', $validated['stock_item_id'])
            ->where('godown_id', $validated['godown_id'])
            ->first();
        if (!$godownStock || $godownStock->quantity < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Insufficient stock in the selected godown.']);
        }

        $voucherType = VoucherType::where('name', 'Sales')->where('company_id', auth()->user()->company_id)->firstOrFail();
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

        // Debit party/cash ledger
        VoucherEntry::create([
            'voucher_id' => $voucher->id,
            'ledger_id' => $validated['party_ledger_id'],
            'amount' => $validated['amount'],
            'type' => 'debit',
            'cost_center_id' => $validated['cost_center_id'],
        ]);

        // Credit sales ledger
        VoucherEntry::create([
            'voucher_id' => $voucher->id,
            'ledger_id' => $validated['sales_ledger_id'],
            'amount' => $validated['amount'],
            'type' => 'credit',
        ]);

        // Record stock entry
        StockEntry::create([
            'voucher_id' => $voucher->id,
            'stock_item_id' => $validated['stock_item_id'],
            'godown_id' => $validated['godown_id'],
            'quantity' => -$validated['quantity'], // Negative for sales
            'rate' => $validated['rate'],
            'company_id' => auth()->user()->company_id,
        ]);

        // Update godown stock
        $godownStock->quantity -= $validated['quantity'];
        $godownStock->save();

        return redirect()->route('vouchers.sales.index')->with('success', 'Sales voucher created successfully.');
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'party_ledger_id' => 'required|exists:ledgers,id',
            'sales_ledger_id' => 'required|exists:ledgers,id',
            'stock_item_id' => 'required|exists:stock_items,id',
            'godown_id' => 'required|exists:godowns,id',
            'quantity' => 'required|numeric|min:0.01',
            'rate' => 'required|numeric|min:0',
            'narration' => 'nullable|string',
            'cost_center_id' => 'nullable|exists:cost_centers,id',
        ]);

        // Revert previous stock entry
        $oldStockEntry = $voucher->stockEntries->first();
        if ($oldStockEntry) {
            $godownStock = GodownStock::where('stock_item_id', $oldStockEntry->stock_item_id)
                ->where('godown_id', $oldStockEntry->godown_id)
                ->first();
            if ($godownStock) {
                $godownStock->quantity += abs($oldStockEntry->quantity); // Reverse the negative quantity
                $godownStock->save();
            }
        }

        // Check new stock availability
        $godownStock = GodownStock::where('stock_item_id', $validated['stock_item_id'])
            ->where('godown_id', $validated['godown_id'])
            ->first();
        if (!$godownStock || $godownStock->quantity < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Insufficient stock in the selected godown.']);
        }

        $voucher->update([
            'date' => $validated['date'],
            'narration' => $validated['narration'],
            'amount' => $validated['amount'],
        ]);

        $voucher->entries()->delete();
        $voucher->stockEntries()->delete();

        // Debit party/cash ledger
        VoucherEntry::create([
            'voucher_id' => $voucher->id,
            'ledger_id' => $validated['party_ledger_id'],
            'amount' => $validated['amount'],
            'type' => 'debit',
            'cost_center_id' => $validated['cost_center_id'],
        ]);

        // Credit sales ledger
        VoucherEntry::create([
            'voucher_id' => $voucher->id,
            'ledger_id' => $validated['sales_ledger_id'],
            'amount' => $validated['amount'],
            'type' => 'credit',
        ]);

        // Record new stock entry
        StockEntry::create([
            'voucher_id' => $voucher->id,
            'stock_item_id' => $validated['stock_item_id'],
            'godown_id' => $validated['godown_id'],
            'quantity' => -$validated['quantity'],
            'rate' => $validated['rate'],
            'company_id' => auth()->user()->company_id,
        ]);

        // Update godown stock
        $godownStock->quantity -= $validated['quantity'];
        $godownStock->save();

        return redirect()->route('vouchers.sales.index')->with('success', 'Sales voucher updated successfully.');
    }

    public function destroy($id)
    {
        $voucher = Voucher::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $stockEntry = $voucher->stockEntries->first();
        if ($stockEntry) {
            $godownStock = GodownStock::where('stock_item_id', $stockEntry->stock_item_id)
                ->where('godown_id', $stockEntry->godown_id)
                ->first();
            if ($godownStock) {
                $godownStock->quantity += abs($stockEntry->quantity);
                $godownStock->save();
            }
        }
        $voucher->delete();
        return redirect()->route('vouchers.sales.index')->with('success', 'Sales voucher deleted successfully.');
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

