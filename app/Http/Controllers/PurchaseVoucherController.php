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

class PurchaseVoucherController extends Controller
{
    public function index()
    {
        $voucherType = VoucherType::where('name', 'Purchase')->where('company_id', auth()->user()->company_id)->first();
        $vouchers = Voucher::where('company_id', auth()->user()->company_id)
            ->where('voucher_type_id', $voucherType->id)
            ->get();
        $ledgers = Ledger::where('company_id', auth()->user()->company_id)->get();
        $costCenters = CostCenter::where('company_id', auth()->user()->company_id)->get();
        $stockItems = StockItem::where('company_id', auth()->user()->company_id)->get();
        $godowns = Godown::where('company_id', auth()->user()->company_id)->get();
        return view('vouchers.purchase.index', compact('vouchers', 'ledgers', 'costCenters', 'stockItems', 'godowns', 'voucherType'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'purchase_ledger_id' => 'required|exists:ledgers,id',
            'party_ledger_id' => 'required|exists:ledgers,id',
            'stock_item_id' => 'required|exists:stock_items,id',
            'godown_id' => 'required|exists:godowns,id',
            'quantity' => 'required|numeric|min:0.01',
            'rate' => 'required|numeric|min:0',
            'narration' => 'nullable|string',
            'cost_center_id' => 'nullable|exists:cost_centers,id',
        ]);

        $voucherType = VoucherType::where('name', 'Purchase')->where('company_id', auth()->user()->company_id)->firstOrFail();
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

        // Debit purchase ledger
        VoucherEntry::create([
            'voucher_id' => $voucher->id,
            'ledger_id' => $validated['purchase_ledger_id'],
            'amount' => $validated['amount'],
            'type' => 'debit',
            'cost_center_id' => $validated['cost_center_id'],
        ]);

        // Credit party/cash ledger
        VoucherEntry::create([
            'voucher_id' => $voucher->id,
            'ledger_id' => $validated['party_ledger_id'],
            'amount' => $validated['amount'],
            'type' => 'credit',
        ]);

        // Record stock entry
        StockEntry::create([
            'voucher_id' => $voucher->id,
            'stock_item_id' => $validated['stock_item_id'],
            'godown_id' => $validated['godown_id'],
            'quantity' => $validated['quantity'], // Positive for purchase
            'rate' => $validated['rate'],
            'company_id' => auth()->user()->company_id,
        ]);

        // Update godown stock
        $godownStock = GodownStock::where('stock_item_id', $validated['stock_item_id'])
            ->where('godown_id', $validated['godown_id'])
            ->first();
        if ($godownStock) {
            $godownStock->quantity += $validated['quantity'];
            $godownStock->save();
        } else {
            GodownStock::create([
                'stock_item_id' => $validated['stock_item_id'],
                'godown_id' => $validated['godown_id'],
                'quantity' => $validated['quantity'],
                'company_id' => auth()->user()->company_id,
            ]);
        }

        return redirect()->route('vouchers.purchase.index')->with('success', 'Purchase voucher created successfully.');
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'purchase_ledger_id' => 'required|exists:ledgers,id',
            'party_ledger_id' => 'required|exists:ledgers,id',
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
                $godownStock->quantity -= $oldStockEntry->quantity; // Reverse the positive quantity
                if ($godownStock->quantity <= 0) {
                    $godownStock->delete();
                } else {
                    $godownStock->save();
                }
            }
        }

        $voucher->update([
            'date' => $validated['date'],
            'narration' => $validated['narration'],
            'amount' => $validated['amount'],
        ]);

        $voucher->entries()->delete();
        $voucher->stockEntries()->delete();

        // Debit purchase ledger
        VoucherEntry::create([
            'voucher_id' => $voucher->id,
            'ledger_id' => $validated['purchase_ledger_id'],
            'amount' => $validated['amount'],
            'type' => 'debit',
            'cost_center_id' => $validated['cost_center_id'],
        ]);

        // Credit party/cash ledger
        VoucherEntry::create([
            'voucher_id' => $voucher->id,
            'ledger_id' => $validated['party_ledger_id'],
            'amount' => $validated['amount'],
            'type' => 'credit',
        ]);

        // Record new stock entry
        StockEntry::create([
            'voucher_id' => $voucher->id,
            'stock_item_id' => $validated['stock_item_id'],
            'godown_id' => $validated['godown_id'],
            'quantity' => $validated['quantity'],
            'rate' => $validated['rate'],
            'company_id' => auth()->user()->company_id,
        ]);

        // Update godown stock
        $godownStock = GodownStock::where('stock_item_id', $validated['stock_item_id'])
            ->where('godown_id', $validated['godown_id'])
            ->first();
        if ($godownStock) {
            $godownStock->quantity += $validated['quantity'];
            $godownStock->save();
        } else {
            GodownStock::create([
                'stock_item_id' => $validated['stock_item_id'],
                'godown_id' => $validated['godown_id'],
                'quantity' => $validated['quantity'],
                'company_id' => auth()->user()->company_id,
            ]);
        }

        return redirect()->route('vouchers.purchase.index')->with('success', 'Purchase voucher updated successfully.');
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
                $godownStock->quantity -= $stockEntry->quantity;
                if ($godownStock->quantity <= 0) {
                    $godownStock->delete();
                } else {
                    $godownStock->save();
                }
            }
        }
        $voucher->delete();
        return redirect()->route('vouchers.purchase.index')->with('success', 'Purchase voucher deleted successfully.');
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

