<?php

// app/Http/Controllers/CurrencyController.php
namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::all();
        return view('currencies.index', compact('currencies'));
    }

    public function create()
    {
        return view('currencies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'symbol' => 'required',
            'currency' => 'required',
            'rate' => 'nullable',
            'code' => 'nullable|max:3',
            'value' => 'nullable|numeric',
        ]);

        Currency::create($request->all() + ['created_by' => Auth::id()]);

        return redirect()->route('currencies.index')->with('success', 'Currency created');
    }

    public function edit(Currency $currency)
    {
        return view('currencies.edit', compact('currency'));
    }

    public function update(Request $request, Currency $currency)
    {
        $request->validate([
            'name' => 'required',
            'symbol' => 'required',
            'currency' => 'required',
            'rate' => 'nullable',
            'code' => 'nullable|max:3',
            'value' => 'nullable|numeric',
        ]);

        $currency->update($request->all());

        return redirect()->route('currencies.index')->with('success', 'Currency updated');
    }

    public function destroy(Currency $currency)
    {
        $currency->delete();
        return redirect()->route('currencies.index')->with('success', 'Currency deleted');
    }
}
