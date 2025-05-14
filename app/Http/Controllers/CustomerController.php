<?php

// app/Http/Controllers/CustomerController.php
namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->get();
        return view('customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'contact_person' => 'required|string',
            'TIN' => 'required|string',
            'VRN' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|email',
            'company' => 'required|string',
            'abbreviation' => 'required|string',
            'status' => 'required|boolean',
            'credit_term' => 'nullable|integer',
        ]);

        $data['created_by'] = Auth::user()->id;
        Customer::create($data);

        return back()->with('success', 'Customer created successfully.');
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'contact_person' => 'required|string',
            'TIN' => 'required|string',
            'VRN' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|email',
            'company' => 'required|string',
            'abbreviation' => 'required|string',
            'status' => 'required|boolean',
            'credit_term' => 'nullable|integer',
        ]);

        $customer->update($data);

        return back()->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return back()->with('success', 'Customer deleted.');
    }
}
