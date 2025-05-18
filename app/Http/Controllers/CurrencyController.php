<?php

// app/Http/Controllers/CurrencyController.php
namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\CurrencyLog;
use Illuminate\Http\Request;
use App\Models\CurrencyLogItem;
use Illuminate\Support\Facades\Auth;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::all();
        return view('currencies.index', compact('currencies'));
    }

    public function create()
    {
        return view('currencies.create');
    }

  /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        request()->validate(
            [
                'name' => 'required',
                'symbol' => 'required',
                'rate' => 'required',
            ]
        );

        Currency::create([
            'name' => $request->input('name'),
            'symbol' => $request->input('symbol'),
            'rate' => $request->input('rate'),
            'created_by' => Auth::user()->id,
            'corridor_rate'=>$request->input('corridor_rate')
        ]);

        // Start of Currency Logs
        $currencyLog=new CurrencyLog();
        $currencyLog->created_date=now();
        $currencyLog->created_by=Auth::user()->id;
        $currencyLog->save();

        $currencies=Currency::latest()->get();
        foreach ($currencies as $currency){
            $currencyLogItem=new CurrencyLogItem();
            $currencyLogItem->currency_log_id=$currencyLog->id;
            $currencyLogItem->currency_id=$currency->id;
            $currencyLogItem->rate=$currency->rate;
            $currencyLogItem->rate=$currency->corridor_rate;
            $currencyLogItem->created_by=Auth::user()->id;
            $currencyLogItem->save();

        }

        // end of Currency Log

        $msg="Currency Added Successfully !";

        return redirect()->route('currencies.index')->with('success',$msg);
    }


    public function edit(Currency $currency)
    {
        return view('currencies.edit', compact('currency'));
    }

     /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Currency $currency)
    {
        request()->validate(
            [
                'name' => 'required',
                'symbol' => 'required',
                'rate' => 'required',
            ]
        );

        $currency->update([
            'name' => $request->input('name'),
            'symbol' => $request->input('symbol'),
            'rate' => $request->input('rate'),
            'corridor_rate'=>$request->input('corridor_rate')

        ]);


        $currencyLog=new CurrencyLog();
        $currencyLog->created_date=now();
        $currencyLog->created_by=Auth::user()->id;
        $currencyLog->save();

        $currencies=Currency::latest()->get();
        foreach ($currencies as $currency){
            $currencyLogItem=new CurrencyLogItem();
            $currencyLogItem->currency_log_id=$currencyLog->id;
            $currencyLogItem->currency_id=$currency->id;
            $currencyLogItem->rate=$currency->rate;
            $currencyLogItem->rate=$currency->corridor_rate;
            $currencyLogItem->created_by=Auth::user()->id;
            $currencyLogItem->save();

        }

        $msg="Currency Updated Successfully !";
        return redirect()->route('currencies.index')->with('success',$msg);
    }

    public function destroy(Currency $currency)
    {
        $currency->delete();
        return redirect()->route('currencies.index')->with('success', 'Currency deleted');
    }
}
