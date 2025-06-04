<?php

namespace App\Http\Controllers;

use App\Models\StockGroup;
use Illuminate\Http\Request;

class StockGroupsController extends Controller
{
    public function index()
    {
        $stockGroups = StockGroup::where('company_id', auth()->user()->company_id)->get();
        return view('stock.groups.index', compact('stockGroups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:stock_groups,id',
        ]);

        StockGroup::create(array_merge($validated, ['company_id' => auth()->user()->company_id]));
        return redirect()->route('stock.groups.index')->with('success', 'Stock Group created successfully.');
    }

    public function update(Request $request, $id)
    {
        $stockGroup = StockGroup::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:stock_groups,id',
        ]);

        $stockGroup->update($validated);
        return redirect()->route('stock.groups.index')->with('success', 'Stock Group updated successfully.');
    }

    public function destroy($id)
    {
        $stockGroup = StockGroup::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $stockGroup->delete();
        return redirect()->route('stock.groups.index')->with('success', 'Stock Group deleted successfully.');
    }
}
