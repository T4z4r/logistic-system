<?php

// app/Http/Controllers/TaxController.php
namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaxController extends Controller
{
    public function index()
    {
        $taxes = Tax::latest()->get();
        return view('taxes.index', compact('taxes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'rate' => 'required|numeric',
        ]);

        Tax::create([
            'name' => $request->name,
            'rate' => $request->rate,
            'status' => $request->status ?? 1,
            'created_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Tax created successfully.');
    }

    public function update(Request $request, $id)
    {
        $tax = Tax::findOrFail($id);
        $tax->update($request->only('name', 'rate', 'status'));

        return redirect()->back()->with('success', 'Tax updated successfully.');
    }

    public function destroy($id)
    {
        Tax::destroy($id);
        return redirect()->back()->with('success', 'Tax deleted successfully.');
    }
}