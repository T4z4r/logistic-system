<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Ledger;
use App\Models\User;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::with(['ledger', 'createdBy', 'currency'])->paginate(10);
        return view('payment-methods.index', compact('paymentMethods'));
    }

    public function active()
    {
        $paymentMethods = PaymentMethod::with(['ledger', 'createdBy', 'currency'])
            ->where('status', 1)
            ->paginate(10);
        return view('payment-methods.index', compact('paymentMethods'));
    }

    public function inactive()
    {
        $paymentMethods = PaymentMethod::with(['ledger', 'createdBy', 'currency'])
            ->where('status', 0)
            ->paginate(10);
        return view('payment-methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        $ledgers = Ledger::all();
        $users = User::all();
        $currencies = Currency::all();
        return view('payment-methods.create', compact('ledgers', 'users', 'currencies'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'ledger_id' => 'required|exists:ledgers,id',
            'currency_id' => 'required|integer|min:0',
            'account_number_usd' => 'nullable|string|max:110',
            'account_number_tzs' => 'nullable|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:255',
            'branch_code' => 'nullable|string|max:255',
            'status' => 'required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        PaymentMethod::create(array_merge(
            $request->only([
                'name',
                'ledger_id',
                'currency_id',
                'account_number_usd',
                'account_number_tzs',
                'branch_name',
                'bank_name',
                'swift_code',
                'branch_code',
                'status',
            ]),
            ['created_by' => Auth::id()]
        ));

        return redirect()->route('payment-methods.list')->with('success', 'Payment method created successfully.');
    }

    public function edit($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $ledgers = Ledger::all();
        $users = User::all();
        $currencies = Currency::all();
        return view('payment-methods.edit', compact('paymentMethod', 'ledgers', 'users', 'currencies'));
    }

    public function update(Request $request, $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'ledger_id' => 'required|exists:ledgers,id',
            'currency_id' => 'required|integer|min:0',
            'account_number_usd' => 'nullable|string|max:110',
            'account_number_tzs' => 'nullable|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:255',
            'branch_code' => 'nullable|string|max:255',
            'status' => 'required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $paymentMethod->update(array_merge(
            $request->only([
                'name',
                'ledger_id',
                'currency_id',
                'account_number_usd',
                'account_number_tzs',
                'branch_name',
                'bank_name',
                'swift_code',
                'branch_code',
                'status',
            ]),
            ['created_by' => Auth::id()]
        ));

        return redirect()->route('payment-methods.list')->with('success', 'Payment method updated successfully.');
    }

    public function destroy($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->delete();
        return redirect()->route('payment-methods.list')->with('success', 'Payment method deleted successfully.');
    }
}