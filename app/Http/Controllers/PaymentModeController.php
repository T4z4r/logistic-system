<?php

namespace App\Http\Controllers;

use App\Models\PaymentMode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentModeController extends Controller
{
    public function index()
    {
        $paymentModes = PaymentMode::paginate(10);
        return view('payment-modes.index', compact('paymentModes'));
    }

    public function active()
    {
        $paymentModes = PaymentMode::where('status', 1)->paginate(10);
        return view('payment-modes.index', compact('paymentModes'));
    }

    public function inactive()
    {
        $paymentModes = PaymentMode::where('status', 0)->paginate(10);
        return view('payment-modes.index', compact('paymentModes'));
    }

    public function create()
    {
        return view('payment-modes.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:payment_modes,name',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        PaymentMode::create($request->only(['name', 'status']));

        return redirect()->route('payment-modes.list')->with('success', 'Payment mode created successfully.');
    }

    public function edit($id)
    {
        $paymentMode = PaymentMode::findOrFail($id);
        return view('payment-modes.edit', compact('paymentMode'));
    }

    public function update(Request $request, $id)
    {
        $paymentMode = PaymentMode::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:payment_modes,name,' . $id,
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $paymentMode->update($request->only(['name', 'status']));

        return redirect()->route('payment-modes.list')->with('success', 'Payment mode updated successfully.');
    }

    public function destroy($id)
    {
        $paymentMode = PaymentMode::findOrFail($id);
        $paymentMode->delete();
        return redirect()->route('payment-modes.list')->with('success', 'Payment mode deleted successfully.');
    }
}
