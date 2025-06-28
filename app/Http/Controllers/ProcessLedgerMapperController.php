<?php

namespace App\Http\Controllers;

use App\Models\ProcessLedgerMapper;
use App\Models\ProcessLedger;
use Illuminate\Http\Request;

class ProcessLedgerMapperController extends Controller
{
    public function index()
    {
        $mappers = ProcessLedgerMapper::with('process')->latest()->get();
        $processes = ProcessLedger::all();
        return view('process_ledger_mappers.index', compact('mappers', 'processes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'process_id' => 'nullable|exists:process_ledgers,id',
            'vat_percentage' => 'nullable|numeric',
            'credit_level' => 'required|integer',
            'debit_level' => 'required|integer',
        ]);

        ProcessLedgerMapper::create($request->all());

        return back()->with('success', 'Ledger Mapper created successfully.');
    }

    public function update(Request $request, ProcessLedgerMapper $mapper)
    {
        $request->validate([
            'process_id' => 'nullable|exists:process_ledgers,id',
            'vat_percentage' => 'nullable|numeric',
            'credit_level' => 'required|integer',
            'debit_level' => 'required|integer',
        ]);

        $mapper->update($request->all());

        return back()->with('success', 'Ledger Mapper updated successfully.');
    }

    public function destroy(ProcessLedgerMapper $mapper)
    {
        $mapper->delete();
        return back()->with('success', 'Ledger Mapper deleted.');
    }
}