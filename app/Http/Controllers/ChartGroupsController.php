<?php

namespace App\Http\Controllers;

use App\Models\AccountGroup;
use Illuminate\Http\Request;

class ChartGroupsController extends Controller
{
    public function index()
    {
        $groups = AccountGroup::where('company_id', auth()->user()->company_id)->get();
        return view('chart-of-accounts.groups.index', compact('groups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:account_groups,id',
            'type' => 'required|in:asset,liability,income,expense',
        ]);

        AccountGroup::create(array_merge($validated, ['company_id' => auth()->user()->company_id]));
        return redirect()->route('chart.groups.index')->with('success', 'Group created successfully.');
    }

    public function update(Request $request, $id)
    {
        $group = AccountGroup::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:account_groups,id',
            'type' => 'required|in:asset,liability,income,expense',
        ]);

        $group->update($validated);
        return redirect()->route('chart.groups.index')->with('success', 'Group updated successfully.');
    }

    public function destroy($id)
    {
        $group = AccountGroup::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $group->delete();
        return redirect()->route('chart.groups.index')->with('success', 'Group deleted successfully.');
    }
}
