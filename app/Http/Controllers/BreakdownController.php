<?php

namespace App\Http\Controllers;

use App\Models\Breakdown;
use App\Models\Truck;
use App\Models\Trip;
use App\Models\BreakdownCategory;
use App\Models\BreakdownItem;
use App\Models\User;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BreakdownController extends Controller
{
    public function index()
    {
        $breakdowns = Breakdown::with(['truck', 'trip', 'currency'])->latest()->paginate(10);
        return view('breakdowns.index', compact('breakdowns'));
    }

    public function create()
    {
        $trucks = Truck::all();
        $trips = Trip::all();
        $categories = BreakdownCategory::all();
        $items = BreakdownItem::all();
        $users = User::all();
        $currencies = Currency::all();

        return view('breakdowns.create', compact('trucks', 'trips', 'categories', 'items', 'users', 'currencies'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'truck_id' => 'nullable|exists:trucks,id',
            'trip_id' => 'nullable|exists:trips,id',
            'breakdown_category_id' => 'nullable|exists:breakdown_categories,id',
            'breakdown_item_id' => 'nullable|exists:breakdown_items,id',
            'description' => 'nullable|string',
            'cost' => 'nullable|string|max:255',
            'type_of_breakdown' => 'nullable|string|max:255',
            'status' => 'required|integer',
            'brakedown_date' => 'nullable|date',
            'state' => 'nullable|integer',
            'currency_id' => 'required|exists:currencies,id',
            'new_cost' => 'nullable|numeric',
            'is_paid' => 'required|boolean',
            'pay_type' => 'required|integer',
            'payment_date' => 'nullable|date',
            'rate' => 'nullable|numeric',
            'real_amount' => 'nullable|numeric',
            'new_cost_currency_id' => 'nullable|exists:currencies,id',
            'breakdown_level' => 'required|integer',
            'payment_level' => 'required|integer',
            'code' => 'nullable|integer',
            'location' => 'nullable|string|max:255',
            'breakdown_type' => 'nullable|string|max:255',
            'closed_date' => 'nullable|date',
            'closed_by_id' => 'nullable|exists:users,id',
            'workshop_status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['added_by_id'] = auth()->id();
        $data['created_by'] = auth()->id();

        Breakdown::create($data);

        return redirect()->route('breakdowns.index')->with('success', 'Breakdown recorded successfully.');
    }

    public function edit($id)
    {
        $breakdown = Breakdown::findOrFail($id);
        $trucks = Truck::all();
        $trips = Trip::all();
        $categories = BreakdownCategory::all();
        $items = BreakdownItem::all();
        $users = User::all();
        $currencies = Currency::all();

        return view('breakdowns.edit', compact('breakdown', 'trucks', 'trips', 'categories', 'items', 'users', 'currencies'));
    }

    public function update(Request $request, $id)
    {
        $breakdown = Breakdown::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'truck_id' => 'nullable|exists:trucks,id',
            'trip_id' => 'nullable|exists:trips,id',
            'breakdown_category_id' => 'nullable|exists:breakdown_categories,id',
            'breakdown_item_id' => 'nullable|exists:breakdown_items,id',
            'description' => 'nullable|string',
            'cost' => 'nullable|string|max:255',
            'type_of_breakdown' => 'nullable|string|max:255',
            'status' => 'required|integer',
            'brakedown_date' => 'nullable|date',
            'state' => 'nullable|integer',
            'currency_id' => 'required|exists:currencies,id',
            'new_cost' => 'nullable|numeric',
            'is_paid' => 'required|boolean',
            'pay_type' => 'required|integer',
            'payment_date' => 'nullable|date',
            'rate' => 'nullable|numeric',
            'real_amount' => 'nullable|numeric',
            'new_cost_currency_id' => 'nullable|exists:currencies,id',
            'breakdown_level' => 'required|integer',
            'payment_level' => 'required|integer',
            'code' => 'nullable|integer',
            'location' => 'nullable|string|max:255',
            'breakdown_type' => 'nullable|string|max:255',
            'closed_date' => 'nullable|date',
            'closed_by_id' => 'nullable|exists:users,id',
            'workshop_status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $breakdown->update($validator->validated());

        return redirect()->route('breakdowns.index')->with('success', 'Breakdown updated successfully.');
    }

    public function show($id)
    {
        $breakdown = Breakdown::with(['truck', 'trip', 'currency'])->findOrFail($id);
        return view('breakdowns.show', compact('breakdown'));
    }

    public function destroy($id)
    {
        $breakdown = Breakdown::findOrFail($id);
        $breakdown->delete();

        return redirect()->route('breakdowns.index')->with('success', 'Breakdown deleted successfully.');
    }

    public function trashed()
    {
        $breakdowns = Breakdown::onlyTrashed()->paginate(10);
        return view('breakdowns.trashed', compact('breakdowns'));
    }

    public function restore($id)
    {
        $breakdown = Breakdown::onlyTrashed()->findOrFail($id);
        $breakdown->restore();

        return redirect()->route('breakdowns.trashed')->with('success', 'Breakdown restored successfully.');
    }

    public function forceDelete($id)
    {
        $breakdown = Breakdown::onlyTrashed()->findOrFail($id);
        $breakdown->forceDelete();

        return redirect()->route('breakdowns.trashed')->with('success', 'Breakdown permanently deleted.');
    }
}
