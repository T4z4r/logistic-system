<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyManagementController extends Controller
{
    public function index()
    {
        $companies = Company::latest()->get();
        $currencies = Currency::all();
        return view('company-management.index', compact('companies', 'currencies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'tax_number' => 'nullable|string|max:50',
            'currency_id' => 'required|exists:currencies,id',
            'financial_year_start' => 'required|date',
        ]);

        $company = Company::create(array_merge($validated, ['user_id' => auth()->id()]));
        $users = User::latest()->get();
        foreach ($users as $user) {
            // if($user->company_id == $company->id){
            $user->update(['company_id' => $company->id]);
            // }
        }

        return redirect()->route('company.management.index')->with('success', 'Company created successfully.');
    }

    public function update(Request $request, $id)
    {
        $company = Company::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'tax_number' => 'nullable|string|max:50',
            'currency_id' => 'required|exists:currencies,id',
            'financial_year_start' => 'required|date',
        ]);

        $company->update($validated);
        $users = User::latest()->get();
        foreach ($users as $user) {
            // if($user->company_id == $company->id){
            $user->update(['company_id' => $company->id]);
            // }
        }
        // User::update(['company_id' => $company->id]);

        return redirect()->route('company.management.index')->with('success', 'Company updated successfully.');
    }

    public function destroy($id)
    {
        $company = Company::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $company->delete();
        return redirect()->route('company.management.index')->with('success', 'Company deleted successfully.');
    }
}
