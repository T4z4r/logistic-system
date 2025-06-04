<?php

namespace App\Http\Controllers;

use App\Models\Godown;
use Illuminate\Http\Request;

class ChartGodownsController extends Controller
{
    public function index()
    {
        $godowns = Godown::where('company_id', auth()->user()->company_id)->get();
        return view('chart-of-accounts.godowns.index', compact('godowns'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
        ]);

        Godown::create(array_merge($validated, ['company_id' => auth()->user()->company_id]));
        return redirect()->route('chart.godowns.index')->with('success', 'Godown created successfully.');
    }

    public function update(Request $request, $id)
    {
        $godown = Godown::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
        ]);

        $godown->update($validated);
        return redirect()->route('chart.godowns.index')->with('success', 'Godown updated successfully.');
    }

    public function destroy($id)
    {
        $godown = Godown::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $godown->delete();
        return redirect()->route('chart.godowns.index')->with('success', 'Godown deleted successfully.');
    }
}
