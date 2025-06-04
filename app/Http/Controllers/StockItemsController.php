<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use App\Models\StockGroup;
use App\Models\Unit;
use App\Models\Godown;
use App\Models\GodownStock;
use Illuminate\Http\Request;

class StockItemsController extends Controller
{
    public function index()
    {
        $stockItems = StockItem::where('company_id', auth()->user()->company_id)->get();
        $stockGroups = StockGroup::where('company_id', auth()->user()->company_id)->get();
        $units = Unit::where('company_id', auth()->user()->company_id)->get();
        $godowns = Godown::where('company_id', auth()->user()->company_id)->get();
        return view('stock.items.index', compact('stockItems', 'stockGroups', 'units', 'godowns'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stock_group_id' => 'required|exists:stock_groups,id',
            'unit_id' => 'required|exists:units,id',
            'opening_stock' => 'nullable|numeric|min:0',
            'rate' => 'nullable|numeric|min:0',
            'godown_id' => 'required|exists:godowns,id',
            'godown_quantity' => 'nullable|numeric|min:0',
        ]);

        $stockItem = StockItem::create([
            'name' => $validated['name'],
            'company_id' => auth()->user()->company_id,
            'stock_group_id' => $validated['stock_group_id'],
            'unit_id' => $validated['unit_id'],
            'opening_stock' => $validated['opening_stock'] ?? 0,
            'rate' => $validated['rate'] ?? 0,
        ]);

        if ($validated['godown_quantity'] > 0) {
            GodownStock::create([
                'stock_item_id' => $stockItem->id,
                'godown_id' => $validated['godown_id'],
                'quantity' => $validated['godown_quantity'],
                'company_id' => auth()->user()->company_id,
            ]);
        }

        return redirect()->route('stock.items.index')->with('success', 'Stock Item created successfully.');
    }

    public function update(Request $request, $id)
    {
        $stockItem = StockItem::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stock_group_id' => 'required|exists:stock_groups,id',
            'unit_id' => 'required|exists:units,id',
            'opening_stock' => 'nullable|numeric|min:0',
            'rate' => 'nullable|numeric|min:0',
            'godown_id' => 'required|exists:godowns,id',
            'godown_quantity' => 'nullable|numeric|min:0',
        ]);

        $stockItem->update([
            'name' => $validated['name'],
            'stock_group_id' => $validated['stock_group_id'],
            'unit_id' => $validated['unit_id'],
            'opening_stock' => $validated['opening_stock'] ?? 0,
            'rate' => $validated['rate'] ?? 0,
        ]);

        $godownStock = GodownStock::where('stock_item_id', $stockItem->id)->where('godown_id', $validated['godown_id'])->first();
        if ($validated['godown_quantity'] > 0) {
            if ($godownStock) {
                $godownStock->update(['quantity' => $validated['godown_quantity']]);
            } else {
                GodownStock::create([
                    'stock_item_id' => $stockItem->id,
                    'godown_id' => $validated['godown_id'],
                    'quantity' => $validated['godown_quantity'],
                    'company_id' => auth()->user()->company_id,
                ]);
            }
        } elseif ($godownStock) {
            $godownStock->delete();
        }

        return redirect()->route('stock.items.index')->with('success', 'Stock Item updated successfully.');
    }

    public function destroy($id)
    {
        $stockItem = StockItem::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $stockItem->delete();
        return redirect()->route('stock.items.index')->with('success', 'Stock Item deleted successfully.');
    }
}
