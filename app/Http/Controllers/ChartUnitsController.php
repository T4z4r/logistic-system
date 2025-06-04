<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class ChartUnitsController extends Controller
{
    public function index()
    {
        $units = Unit::where('company_id', auth()->user()->company_id)->get();
        return view('chart-of-accounts.units.index', compact('units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:50',
            'is_compound' => 'boolean',
            'base_unit_id' => 'nullable|exists:units,id',
            'conversion_factor' => 'nullable|numeric|min:0',
        ]);

        Unit::create(array_merge($validated, ['company_id' => auth()->user()->company_id]));
        return redirect()->route('chart.units.index')->with('success', 'Unit created successfully.');
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:50',
            'is_compound' => 'boolean',
            'base_unit_id' => 'nullable|exists:units,id',
            'conversion_factor' => 'nullable|numeric|min:0',
        ]);

        $unit->update($validated);
        return redirect()->route('chart.units.index')->with('success', 'Unit updated successfully.');
    }

    public function destroy($id)
    {
        $unit = Unit::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $unit->delete();
        return redirect()->route('chart.units.index')->with('success', 'Unit deleted successfully.');
    }
}
