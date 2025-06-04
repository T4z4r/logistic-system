<?php

namespace App\Http\Controllers;

use App\Models\CostCenter;
use App\Models\CostCategory;
use Illuminate\Http\Request;

class ChartCostCentersController extends Controller
{
    public function index()
    {
        $costCenters = CostCenter::where('company_id', auth()->user()->company_id)->get();
        $costCategories = CostCategory::where('company_id', auth()->user()->company_id)->get();
        return view('chart-of-accounts.cost-centers.index', compact('costCenters', 'costCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cost_category_id' => 'nullable|exists:cost_categories,id',
        ]);

        CostCenter::create(array_merge($validated, ['company_id' => auth()->user()->company_id]));
        return redirect()->route('chart.cost-centers.index')->with('success', 'Cost Center created successfully.');
    }

    public function update(Request $request, $id)
    {
        $costCenter = CostCenter::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cost_category_id' => 'nullable|exists:cost_categories,id',
        ]);

        $costCenter->update($validated);
        return redirect()->route('chart.cost-centers.index')->with('success', 'Cost Center updated successfully.');
    }

    public function destroy($id)
    {
        $costCenter = CostCenter::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $costCenter->delete();
        return redirect()->route('chart.cost-centers.index')->with('success', 'Cost Center deleted successfully.');
    }
}
