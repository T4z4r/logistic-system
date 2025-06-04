<?php

namespace App\Http\Controllers;

use App\Models\VoucherType;
use Illuminate\Http\Request;

class ChartVoucherTypesController extends Controller
{
    public function index()
    {
        $voucherTypes = VoucherType::where('company_id', auth()->user()->company_id)->get();
        return view('chart-of-accounts.voucher-types.index', compact('voucherTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'prefix' => 'nullable|string|max:50',
            'numbering' => 'required|in:manual,auto',
        ]);

        VoucherType::create(array_merge($validated, ['company_id' => auth()->user()->company_id]));
        return redirect()->route('chart.voucher-types.index')->with('success', 'Voucher Type created successfully.');
    }

    public function update(Request $request, $id)
    {
        $voucherType = VoucherType::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'prefix' => 'nullable|string|max:50',
            'numbering' => 'required|in:manual,auto',
        ]);

        $voucherType->update($validated);
        return redirect()->route('chart.voucher-types.index')->with('success', 'Voucher Type updated successfully.');
    }

    public function destroy($id)
    {
        $voucherType = VoucherType::where('id', $id)->where('company_id', auth()->user()->company_id)->firstOrFail();
        $voucherType->delete();
        return redirect()->route('chart.voucher-types.index')->with('success', 'Voucher Type deleted successfully.');
    }
}
