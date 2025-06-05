<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use App\Models\AccountGroup;
use Illuminate\Http\Request;

class ChartLedgersController extends Controller
{
    public function index()
    {
        $ledgers = Ledger::where('company_id', auth()->user()->company_id)->get();
        $groups = AccountGroup::where('company_id', auth()->user()->company_id)->get();
        return view('chart-of-accounts.ledgers.index', compact('ledgers', 'groups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'group_id' => 'required|exists:account_groups,id',
            'opening_balance' => 'nullable|numeric|min:0',
            'contact_details' => 'nullable|string',
        ]);
        // $validated['company_id'] = auth()->user()->company_id??1;
        Ledger::create(array_merge($validated, ['company_id' => auth()->user()->company_id]));
        return redirect()->route('chart.ledgers.index')->with('success', 'Ledger created successfully.');
    }

    public function update(Request $request, $id)
    {
        $ledger = Ledger::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'group_id' => 'required|exists:account_groups,id',
            'opening_balance' => 'nullable|numeric|min:0',
            'contact_details' => 'nullable|string',
        ]);

        $validated['company_id'] = auth()->user()->company_id??1;

        $ledger->update($validated);
        return redirect()->route('chart.ledgers.index')->with('success', 'Ledger updated successfully.');
    }

    public function destroy($id)
    {
        $ledger = Ledger::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $ledger->delete();
        return redirect()->route('chart.ledgers.index')->with('success', 'Ledger deleted successfully.');
    }
}
