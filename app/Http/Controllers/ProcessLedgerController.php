<?php

namespace App\Http\Controllers;

use App\Models\ProcessLedger;
use Illuminate\Http\Request;

class ProcessLedgerController extends Controller
{
    public function index()
    {
        $ledgers = ProcessLedger::latest()->get();
        return view('process_ledgers.index', compact('ledgers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        ProcessLedger::create($request->only('name'));

        return back()->with('success', 'Process Ledger created successfully.');
    }

    public function update(Request $request, ProcessLedger $processLedger)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $processLedger->update($request->only('name'));

        return back()->with('success', 'Process Ledger updated successfully.');
    }

    public function destroy(ProcessLedger $processLedger)
    {
        $processLedger->delete();
        return back()->with('success', 'Process Ledger deleted successfully.');
    }
}