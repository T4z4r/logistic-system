<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\OrgRegion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with('region')->get();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        $regions = OrgRegion::all();
        return view('suppliers.create', compact('regions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
        ]);

        Supplier::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'company' => $request->company,
            'credit_term' => $request->credit_term,
            'address' => $request->address,
            'tin_number' => $request->tin_number,
            'vrn_number' => $request->vrn_number,
            'bank_name' => $request->bank_name,
            'bank_account' => $request->bank_account,
            'user_type' => 1,
            'status' => $request->status ?? 1,
            'created_by' => Auth::id(),
            'balance_ledger' => $request->balance_ledger,
            'region_id' => $request->region_id,
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function edit(Supplier $supplier)
    {
        $regions = OrgRegion::all();
        return view('suppliers.edit', compact('supplier', 'regions'));
    }

    public function update(Request $request,  $supplierId)
    {

        $supplier = Supplier::where('id', $supplierId)->first();
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
        ]);

        $supplier->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'company' => $request->company,
            'credit_term' => $request->credit_term,
            'address' => $request->address,
            'tin_number' => $request->tin_number,
            'vrn_number' => $request->vrn_number,
            'bank_name' => $request->bank_name,
            'bank_account' => $request->bank_account,
            'status' => $request->status,
            'balance_ledger' => $request->balance_ledger,
            'region_id' => $request->region_id,
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy($supplierId)
    {
        $supplier = Supplier::where('id', $supplierId)->first();
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}