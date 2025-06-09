<?php

namespace App\Http\Controllers;

use App\Models\CommonCost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommonCostController extends Controller
{
    public function index()
    {
        $commonCosts = CommonCost::with(['ledger', 'createdBy'])->paginate(100);
        return view('common-costs.index', compact('commonCosts'));
    }

    public function editable()
    {
        $commonCosts = CommonCost::with(['ledger', 'createdBy'])
            ->where('editable', 1)
            ->paginate(10);
        return view('common-costs.editable', compact('commonCosts'));
    }

    public function nonEditable()
    {
        $commonCosts = CommonCost::with(['ledger', 'createdBy'])
            ->where('editable', 0)
            ->paginate(10);
        return view('common-costs.non-editable', compact('commonCosts'));
    }

    public function create()
    {
        $users = User::where('status', 1)->get();
        return view('common-costs.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:common_costs',
            'ledger_id' => 'required|exists:users,id',
            'created_by' => 'required|exists:users,id',
            'vat' => 'required|boolean',
            'editable' => 'required|boolean',
            'advancable' => 'required|integer|min:0',
            'return' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        CommonCost::create($request->only([
            'name',
            'ledger_id',
            'created_by',
            'vat',
            'editable',
            'advancable',
            'return',
        ]));

        return redirect()->route('common-costs.list')->with('success', 'Common cost created successfully.');
    }

    public function edit($id)
    {
        $commonCost = CommonCost::findOrFail($id);
        $users = User::where('status', 1)->get();
        return view('common-costs.edit', compact('commonCost', 'users'));
    }

    public function update(Request $request, $id)
    {
        $commonCost = CommonCost::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:common_costs,name,' . $commonCost->id,
            'ledger_id' => 'required|exists:users,id',
            'created_by' => 'required|exists:users,id',
            'vat' => 'required|boolean',
            'editable' => 'required|boolean',
            'advancable' => 'required|integer|min:0',
            'return' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $commonCost->update($request->only([
            'name',
            'ledger_id',
            'created_by',
            'vat',
            'editable',
            'advancable',
            'return',
        ]));

        return redirect()->route('common-costs.list')->with('success', 'Common cost updated successfully.');
    }

    public function destroy($id)
    {
        $commonCost = CommonCost::findOrFail($id);
        $commonCost->delete();
        return redirect()->route('common-costs.list')->with('success', 'Common cost deleted successfully.');
    }
}
