<?php

// app/Http/Controllers/OffBudgetCategoryController.php
namespace App\Http\Controllers;

use App\Models\OffBudgetCategory;
use App\Models\TruckCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OffBudgetCategoryController extends Controller
{
    public function index()
    {
        $categories = OffBudgetCategory::with(['creator', 'cost'])->get();
        $costs = TruckCost::all();
        return view('off_budget_categories.index', compact('categories', 'costs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cost_id' => 'required|exists:truck_costs,id',
        ]);

        OffBudgetCategory::create([
            'name' => $request->name,
            'cost_id' => $request->cost_id,
            'created_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Off Budget Category created successfully.');
    }

    public function update(Request $request, $id)
    {
        $category = OffBudgetCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'cost_id' => 'required|exists:truck_costs,id',
        ]);

        $category->update([
            'name' => $request->name,
            'cost_id' => $request->cost_id,
        ]);

        return redirect()->back()->with('success', 'Off Budget Category updated successfully.');
    }

    public function destroy($id)
    {
        OffBudgetCategory::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Off Budget Category deleted.');
    }
}
