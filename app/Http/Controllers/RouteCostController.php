<?php

namespace App\Http\Controllers;

use App\Models\RouteCost;
use App\Models\Route;
use App\Models\CommonCost;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RouteCostController extends Controller
{
    public function index($route_id)
    {
        $route = Route::findOrFail($route_id);
        $costs = RouteCost::where('route_id', $route_id)
            ->with(['cost', 'currency', 'createdBy'])
            ->paginate(10);
        return view('route-costs.index', compact('route', 'costs'));
    }

    public function create($route_id)
    {
        $route = Route::findOrFail($route_id);
        $commonCosts = CommonCost::where('status', 1)->get();
        $currencies = Currency::all();
        return view('route-costs.create', compact('route', 'commonCosts', 'currencies'));
    }

    public function store(Request $request, $route_id)
    {
        $route = Route::findOrFail($route_id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'account_code' => 'required|string|max:255',
            'cost_id' => 'required|exists:common_costs,id',
            'currency_id' => 'required|exists:currencies,id',
            'rate' => 'required|numeric|min:0',
            'real_amount' => 'required|numeric|min:0',
            'quantity' => 'nullable|numeric|min:0',
            'vat' => 'boolean',
            'editable' => 'boolean',
            'type' => 'required|string|in:All,Specific', // Adjust options as needed
            'advancable' => 'integer|min:0',
            'return' => 'boolean',
            'status' => 'integer|in:0,1', // Adjust status values as needed
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('modal', 'create-cost-' . $route_id);
        }

        RouteCost::create(array_merge(
            $request->only([
                'name',
                'amount',
                'account_code',
                'cost_id',
                'currency_id',
                'rate',
                'real_amount',
                'quantity',
                'vat',
                'editable',
                'type',
                'advancable',
                'return',
                'status',
            ]),
            ['route_id' => $route_id, 'created_by' => Auth::id()]
        ));

        return redirect()->route('routes.show', $route_id)->with('success', 'Route cost created successfully.');
    }

    public function edit($route_id, $id)
    {
        $route = Route::findOrFail($route_id);
        $cost = RouteCost::findOrFail($id);
        $commonCosts = CommonCost::where('status', 1)->get();
        $currencies = Currency::all();
        return view('route-costs.edit', compact('route', 'cost', 'commonCosts', 'currencies'));
    }

    public function update(Request $request, $route_id, $id)
    {
        $route = Route::findOrFail($route_id);
        $cost = RouteCost::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'account_code' => 'required|string|max:255',
            'cost_id' => 'required|exists:common_costs,id',
            'currency_id' => 'required|exists:currencies,id',
            'rate' => 'required|numeric|min:0',
            'real_amount' => 'required|numeric|min:0',
            'quantity' => 'nullable|numeric|min:0',
            'vat' => 'boolean',
            'editable' => 'boolean',
            'type' => 'required|string|in:All,Specific',
            'advancable' => 'integer|min:0',
            'return' => 'boolean',
            'status' => 'integer mínima:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('modal', 'edit-cost-' . $id);
        }

        $cost->update(array_merge(
            $request->only([
                'name',
                'amount',
                'account_code',
                'cost_id',
                'currency_id',
                'rate',
                'real_amount',
                'quantity',
                'vat',
                'editable',
                'type',
                'advancable',
                'return',
                'status',
            ]),
            ['created_by' => Auth::id()]
        ));

        return redirect()->route('routes.show', $route_id)->with('success', 'Route cost updated successfully.');
    }

    public function destroy($route_id, $id)
    {
        $cost = RouteCost::findOrFail($id);
        $cost->delete();
        return redirect()->route('routes.show', $route_id)->with('success', 'Route cost deleted successfully.');
    }
}