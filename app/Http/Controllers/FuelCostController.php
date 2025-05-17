<?php

namespace App\Http\Controllers;

use App\Models\FuelCost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FuelCostController extends Controller
{
    public function index()
    {
        $fuelCosts = FuelCost::with(['ledger', 'createdBy'])->paginate(10);
        return view('fuel-costs.index', compact('fuelCosts'));
    }

    public function editable()
    {
        $fuelCosts = FuelCost::with(['ledger', 'createdBy'])
            ->where('editable', 1)
            ->paginate(10);
        return view('fuel-costs.editable', compact('fuelCosts'));
    }

    public function nonEditable()
    {
        $fuelCosts = FuelCost::with(['ledger', 'createdBy'])
            ->where('editable', 0)
            ->paginate(10);
        return view('fuel-costs.non-editable', compact('fuelCosts'));
    }

    public function create()
    {
        $users = User::where('status', 1)->get();
        return view('fuel-costs.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:fuel_costs',
            'ledger_id' => 'required|exists:users,id',
            'created_by' => 'required|exists:users,id',
            'vat' => 'required|boolean',
            'editable' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        FuelCost::create($request->only([
            'name',
            'ledger_id',
            'created_by',
            'vat',
            'editable',
        ]));

        return redirect()->route('fuel-costs.list')->with('success', 'Fuel cost created successfully.');
    }

    public function edit($id)
    {
        $fuelCost = FuelCost::findOrFail($id);
        $users = User::where('status', 1)->get();
        return view('fuel-costs.edit', compact('fuelCost', 'users'));
    }

    public function update(Request $request, $id)
    {
        $fuelCost = FuelCost::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:fuel_costs,name,' . $fuelCost->id,
            'ledger_id' => 'required|exists:users,id',
            'created_by' => 'required|exists:users,id',
            'vat' => 'required|boolean',
            'editable' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $fuelCost->update($request->only([
            'name',
            'ledger_id',
            'created_by',
            'vat',
            'editable',
        ]));

        return redirect()->route('fuel-costs.list')->with('success', 'Fuel cost updated successfully.');
    }

    public function destroy($id)
    {
        $fuelCost = FuelCost::findOrFail($id);
        $fuelCost->delete();
        return redirect()->route('fuel-costs.list')->with('success', 'Fuel cost deleted successfully.');
    }
}
