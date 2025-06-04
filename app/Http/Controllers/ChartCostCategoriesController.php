<?php

namespace App\Http\Controllers;

use App\Models\CostCategory;
use Illuminate\Http\Request;

class ChartCostCategoriesController extends Controller
{
    public function index()
    {
        $costCategories = CostCategory::where('company_id', auth()->user()->company_id)->get();
        return view('chart-of-accounts.cost-categories.index', compact('costCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        CostCategory::create(array_merge($validated, ['company_id' => auth()->user()->company_id]));
        return redirect()->route('chart.cost-categories.index')->with('success', 'Cost Category created successfully.');
    }

    public function update(Request $request, $id)
    {
        $costCategory = CostCategory::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $costCategory->update($validated);
        return redirect()->route('chart.cost-categories.index')->with('success', 'Cost Category updated successfully.');
    }

    public function destroy($id)
    {
        $costCategory = CostCategory::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $costCategory->delete();
        return redirect()->route('chart.cost-categories.index')->with('success', 'Cost Category deleted successfully.');
    }
}
