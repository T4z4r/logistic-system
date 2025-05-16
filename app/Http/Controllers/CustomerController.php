<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with('createdBy')->paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function active()
    {
        $customers = Customer::with('createdBy')
            ->where('status', 1)
            ->paginate(10);
        return view('customers.active', compact('customers'));
    }

    public function inactive()
    {
        $customers = Customer::with('createdBy')
            ->where('status', 0)
            ->paginate(10);
        return view('customers.inactive', compact('customers'));
    }

    public function create()
    {
        $users = User::where('status', 1)->get();
        return view('customers.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact_person' => 'required|string|max:255',
            'TIN' => 'required|string|max:255|unique:customers,TIN',
            'VRN' => 'required|string|max:255|unique:customers,VRN',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email',
            'company' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:50',
            'created_by' => 'required|exists:users,id',
            'status' => 'required|boolean',
            'credit_term' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Customer::create($request->only([
            'contact_person',
            'TIN',
            'VRN',
            'phone',
            'address',
            'email',
            'company',
            'abbreviation',
            'created_by',
            'status',
            'credit_term',
        ]));

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $users = User::where('status', 1)->get();
        return view('customers.edit', compact('customer', 'users'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'contact_person' => 'required|string|max:255',
            'TIN' => 'required|string|max:255|unique:customers,TIN,' . $customer->id,
            'VRN' => 'required|string|max:255|unique:customers,VRN,' . $customer->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email,' . $customer->id,
            'company' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:50',
            'created_by' => 'required|exists:users,id',
            'status' => 'required|boolean',
            'credit_term' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $customer->update($request->only([
            'contact_person',
            'TIN',
            'VRN',
            'phone',
            'address',
            'email',
            'company',
            'abbreviation',
            'created_by',
            'status',
            'credit_term',
        ]));

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}
