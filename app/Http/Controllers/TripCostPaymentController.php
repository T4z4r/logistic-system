<?php

namespace App\Http\Controllers\Accounts;


use Carbon\Carbon;
use App\Models\Trip;
use App\Models\User;
use App\Models\Truck;
use App\Models\FuelCost;
use App\Models\RouteCost;
use App\Models\TruckCost;
use App\Models\Allocation;
use App\Models\Retirement;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\AllocationCost;
use App\Models\AdvancePayement;
use App\Models\TruckAllocation;
use App\Models\TruckCostPayment;
use App\Models\RetirementPayment;
use App\Models\Settings\Currency;
use App\Models\Transaction;
use App\Http\Controllers\Controller;
use App\Notifications\EmailRequests;
use Illuminate\Support\Facades\Auth;
use App\Models\Ledger;
use App\Models\AllocationCostPayment;
use Illuminate\Support\Facades\Notification;

class TripCostPaymentController extends Controller
{
    // For Paying Allocation Cost
    public function allocation_cost_payment(Request $request)
    {

        request()->validate(
            [
                'truck_id' => 'required',
                'cost_id' => 'required',
                'amount' => 'required',
            ]
        );
        $id = AllocationCost::find($request->cost_id);

        $payment = new AllocationCostPayment();
        $payment->amount = $request->amount;
        $payment->allocation_id = $id->allocation_id;
        $payment->truck_id = $request->truck_id;
        $payment->cost_id = $request->cost_id;
        $payment->paid_date = Carbon::now()->format('Y-m-d');
        $payment->paid_by = Auth::user()->id;

        // Checking  if the payment has sufficient balance
        $debit = Transaction::where('ledger_id', $request->credit_ledger)->where('type', 2)->sum('amount');
        $credit = Transaction::where('ledger_id', $request->credit_ledger)->where('type', 1)->sum('amount');
        $balance = $debit - $credit;
        $amount = $request->amount * $id->currency->rate;
        $difference = $balance - $amount;
        // dd($difference);
        // if ($difference < 0) {
        //     return redirect()->back()->with('error', 'Insufficient Balance');
        // }
        //End of balance check

        // Start of Transaction
        $cost = AllocationCost::find($request->cost_id);

        if ($cost->quantity > 0) {
            $routeCost = RouteCost::where('name', $cost->name)->first();
            $fuelCost = FuelCost::where('id', $routeCost->cost_id)->first();
            $expense_ledger = Ledger::where('id', $fuelCost->ledger_id)->first();
        } else {
            $expense_ledger = Ledger::where('code', $cost->account_code)->first();
        }

        $truck = Truck::where('id', $request->truck_id)->first();

        $credit_ledger = $request->credit_ledger;
        $debit_ledger = $expense_ledger->id;


        $creditTranscation = new Transaction();
        $creditTranscation->ledger_id = $credit_ledger;
        $creditTranscation->amount = $request["amount"];
        $creditTranscation->process = 'Allocation Cost Payment';
        $creditTranscation->process_id = $cost->id;
        $creditTranscation->category = $truck->plate_number;
        $creditTranscation->description = 'Paid ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->ref_no;
        $creditTranscation->type = 1;
        $creditTranscation->save();

        $debitTranscation = new Transaction();
        $debitTranscation->ledger_id = $debit_ledger;
        $debitTranscation->amount = $request["amount"];
        $debitTranscation->description = 'Paid ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->ref_no;
        $debitTranscation->process = 'Allocation Cost Payment';
        $debitTranscation->process_id = $cost->id;
        $debitTranscation->category = $truck->plate_number;
        $debitTranscation->type = 2;
        $debitTranscation->save();
        // end of Transaction
        // Start of Tax
        if ($id->vat == '1') {

            // start of Tax Transaction
            $tax_ledger = Ledger::where('code', '2200')->first()->id;
            $tax_amount = ($id->amount * $id->currency->rate) * (18 / 100);
            $taxTranscation = new Transaction();

            $taxTranscation->ledger_id = $tax_ledger;
            $taxTranscation->amount = $tax_amount;
            $taxTranscation->description = 'Paid VAT from Expense ' . $cost->name . ' of Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->ref_no;
            $taxTranscation->process = 'Allocation Cost Payment';
            $taxTranscation->category = $truck->plate_number;
            $taxTranscation->process_id = $cost->id;
            $taxTranscation->type = 1;
            $taxTranscation->save();
            // End of Tax Transaction
        }
        // End of Tax
        $payment->save();
        $msg = 'Allocation Expense Paid Successfully !';
        return back()->with('msg', $msg);
    }

    // For Advance Payment

    public function advance_payment(Request $request)
    {

        request()->validate(
            [
                'truck_id' => 'required',
                'cost_id' => 'required',
                'amount' => 'required',
            ]
        );
        $id = AllocationCost::find($request->cost_id);
        $new_paid_amount = $id->paid_amount + $request->amount;
        $id->paid_amount = $new_paid_amount;
        $remaining = $id->amount - ($new_paid_amount + $request->dedeuction_amount);

        $id->remaining_amount = $remaining;
        if ($remaining == 0) {
            $id->payment_status = 3;
        }
        $truck_allocation = TruckAllocation::where('id', $request->truck_id)->where('allocation_id', $id->allocation_id)->first();

        $payment = new AllocationCostPayment();
        $payment->amount = $request->amount;
        $payment->allocation_id = $id->allocation_id;
        $payment->truck_id = $truck_allocation->truck_id;
        $payment->cost_id = $request->cost_id;
        $payment->paid_date = Carbon::now()->format('Y-m-d');
        $payment->paid_by = Auth::user()->id;


        // Checking  if the payment has sufficient balance
        $debit = Transaction::where('ledger_id', $request->credit_ledger)->where('type', 2)->sum('amount');
        $credit = Transaction::where('ledger_id', $request->credit_ledger)->where('type', 1)->sum('amount');
        $balance = $debit - $credit;
        $amount = $request->amount * $id->currency->rate;
        $difference = $balance - $amount;
        // dd($difference);
        if ($difference < 0) {
            return redirect()->back()->with('error', 'Insufficient Balance');
        }
        //End of balance check

        // For Retirement Payment
        $retirements = Retirement::where('driver_id', $truck_allocation->driver_id)->where('status', '<', 3)->get();

        if (count($retirements) > 0) {
            // Start of Transaction
            $cost = AllocationCost::find($request->cost_id);
            $expense_ledger = Ledger::where('id', $cost->route_cost->cost->ledger_id)->first();
            $truck = Truck::where('id', $truck_allocation->truck_id)->first();

            $credit_ledger = $request->credit_ledger;
            $debit_ledger = $expense_ledger->id;
            $driver_ledger = Ledger::where('client_code', config('constants.ledger.driver'))->first();
            $total_payment_amount = $request->deduction_amount;

            if ($total_payment_amount > 0) {


                foreach ($retirements as $retirement) {


                    $retirement_payments = RetirementPayment::where('retirement_id', $retirement->id)->sum('amount');
                    $remaining = $retirement->amount - $retirement_payments;
                    $payment_amount = $total_payment_amount + $remaining;

                    if (($retirement->amount >= $payment_amount) && ($payment_amount > 0)) {

                        $retirement_payment = RetirementPayment::create([
                            'retirement_id' => $retirement->id,
                            'currency_id' => $cost->currency_id,
                            'amount' => $request->deduction_amount,
                            'rate' => $cost->currency->rate,
                            'real_amount' => ($remaining * $cost->currency->rate),
                            'date' => date('Y-m-d'),
                            'created_by' => Auth::user()->id
                        ]);


                        // Driver Amount
                        $driverTranscation = new Transaction();
                        $driverTranscation->ledger_id = $driver_ledger->id;
                        $driverTranscation->amount = ($payment_amount) * $retirement->currency->rate;
                        $driverTranscation->description = 'Received ' . $retirement->reason . ' debt payment from ' . $retirement->driver->fname . ' ' . $retirement->driver->mname . ' ' . $retirement->driver->lname;
                        $driverTranscation->type = 1;
                        $driverTranscation->process = 'Deduction Expense';
                        $driverTranscation->process_id = $retirement_payment->id;
                        $driverTranscation->category = 'General Expense';
                        $driverTranscation->created_by = Auth::user()->id;
                        $driverTranscation->save();
                        // Bank Amount
                        $debitTranscation = new Transaction();
                        $debitTranscation->ledger_id = $credit_ledger;
                        $debitTranscation->amount = ($payment_amount) * $retirement->currency->rate;
                        $debitTranscation->description = 'Received ' . $retirement->reason . ' debt payment from ' . $retirement->driver->fname . ' ' . $retirement->driver->mname . ' ' . $retirement->driver->lname;
                        $debitTranscation->type = 2;
                        $debitTranscation->process = 'Driver Debt Payment';
                        $debitTranscation->category = 'General Expense';
                        $debitTranscation->process_id = $retirement_payment->id;
                        $debitTranscation->created_by = Auth::user()->id;
                        $debitTranscation->save();

                        $total_payment_amount = $total_payment_amount - $payment_amount;

                        if ($retirement->amount == ($payment_amount + $retirement_payments)) {
                            $retirement->status = 3;
                            $retirement->update();
                        }
                    }

                    // dd($total_payment_amount);
                }
            }
            $creditTranscation = new Transaction();
            $creditTranscation->ledger_id = $credit_ledger;
            $creditTranscation->amount = ($request->amount * $cost->currency->rate);
            $creditTranscation->process = 'Deduction Expense';
            $creditTranscation->category = $truck->plate_number;
            $creditTranscation->process_id = $cost->id;
            $creditTranscation->description = 'Paid ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->ref_no;
            $creditTranscation->type = 1;
            $creditTranscation->save();

            $debitTranscation = new Transaction();
            $debitTranscation->ledger_id = $debit_ledger;
            $debitTranscation->amount = $request->amount * $cost->currency->rate;
            $debitTranscation->description = 'Paid ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->ref_no;
            $debitTranscation->process = 'Deduction Expense';
            $debitTranscation->category = $truck->plate_number;
            $debitTranscation->process_id = $cost->id;
            $debitTranscation->type = 2;
            $debitTranscation->save();


            // end of Transaction
            // Start of Tax
            if ($id->vat == '1') {

                // start of Tax Transaction
                $tax_ledger = Ledger::where('code', '2200')->first()->id;
                $tax_amount = ($id->amount * $id->currency->rate) * (18 / 100);
                $taxTranscation = new Transaction();

                $taxTranscation->ledger_id = $tax_ledger;
                $taxTranscation->amount = $tax_amount;
                $taxTranscation->description = 'Paid VAT from Expense ' . $cost->name . ' of Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->ref_no;
                $taxTranscation->process = 'Deduction Expense';
                $taxTranscation->category = $truck->plate_number;
                $taxTranscation->process_id = $cost->id;
                $taxTranscation->type = 1;
                $taxTranscation->save();
                // End of Tax Transaction
            }
        } else {
            return redirect()->back()->with('error', 'Sorry, The Selected Driver Has No Any Registered Debt !');
        }

        // End of Tax
        $payment->save();

        $id->update();

        // $truck_allocation = TruckAllocation::where('id', $request->truck_id)->where('allocation_id', $id->allocation_id)->first();

        // For Advance Payment Logging
        $advance = new AdvancePayement();
        // $advance->truck_id = $truck_allocation->truck_id;
        $advance->driver_id = $truck_allocation->driver_id;
        $advance->cost_id = $request->cost_id;
        $advance->cost_id = $request->amount * $id->currency->rate;
        $advance->allocation_id = $id->allocation_id;
        $advance->currency_id = $id->currency_id;
        $advance->nature = 'Allocation Cost';
        $advance->save();




        $msg = 'Allocation Expense Paid Successfully !';
        return back()->with('msg', $msg);
    }

    // For Paying Truck Cost
    public function truck_cost_payment(Request $request)
    {

        request()->validate(
            [
                'truck_id' => 'required',
                'cost_id' => 'required',
                'amount' => 'required',
            ]
        );

        $id = TruckCost::find($request->cost_id);
        $payment = new TruckCostPayment();
        $payment->amount = $request->amount;
        $payment->truck_id = $request->truck_id;
        $payment->cost_id = $request->cost_id;
        $payment->paid_date = Carbon::now()->format('Y-m-d');
        $payment->paid_by = Auth::user()->id;
        $payment->save();
        // Start of Transaction
        $cost = TruckCost::find($request->cost_id);
        // dd($cost);
        $expense_ledger = Ledger::where('code', $cost->account_code)->first();
        $truck = Truck::where('id', $request->truck_id)->first();

        $credit_ledger = $request->credit_ledger;
        $debit_ledger = $expense_ledger->id;


        $creditTranscation = new Transaction();
        $creditTranscation->ledger_id = $credit_ledger;
        $creditTranscation->amount = $request["amount"];
        $creditTranscation->description = 'Paid ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->allocation->ref_no;
        $creditTranscation->type = 1;
        $creditTranscation->category = $truck->plate_number;
        $creditTranscation->process = 'Truck Cost Payment';
        $creditTranscation->process_id = $cost->id;
        $creditTranscation->process_id = Auth::user()->created_by;
        $creditTranscation->save();

        $debitTranscation = new Transaction();
        $debitTranscation->ledger_id = $debit_ledger;
        $debitTranscation->amount = $request["amount"];
        $debitTranscation->description = 'Paid ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->allocation->ref_no;
        $debitTranscation->type = 2;
        $debitTranscation->category = $truck->plate_number;
        $debitTranscation->process = 'Truck Cost Payment';
        $debitTranscation->process_id = $cost->id;
        $debitTranscation->created_by = Auth::user()->id;

        $debitTranscation->save();
        // end of Transaction


        // Start of Tax
        if ($id->vat == '1') {

            // start of Tax Transaction
            $tax_ledger = Ledger::where('code', '2200')->first()->id;
            $tax_amount = ($id->amount * $id->currency->rate) * (18 / 100);
            $taxTranscation = new Transaction();

            $taxTranscation->ledger_id = $tax_ledger;
            $taxTranscation->amount = $tax_amount;
            $taxTranscation->description = 'Paid VAT from Expense ' . $cost->name . ' of Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->ref_no;
            $taxTranscation->type = 1;
            $taxTranscation->process = 'Truck Cost Payment';
            $taxTranscation->category = $truck->plate_number;
            $taxTranscation->process_id = $cost->id;
            $taxTranscation->save();
            // End of Tax Transaction
        }
        // End of Tax

        $id->status = 3;
        $id->update();

        $msg = 'Additional Truck Expense Paid Successfully !';
        return back()->with('msg', $msg);
    }



    // Start of Bulk Trip Cost Payment

    public function bulk_allocation_cost_payment(Request $request)
    {

        if ($request->input('selectedRows') == null) {
            return back()->with('error', 'Please Select Cost to Pay');
        }
        $selectedRows = $request->input('selectedRows');
        $truck = Truck::where('id', $request->truck_id)->first();

        //  $request->all();
        // foreach ($request->moreFields as $key => $value) {
        foreach ($selectedRows as $rowId) {

            $id = AllocationCost::find($rowId);
            // Checking  if the payment has sufficient balance
            $debit = Transaction::where('ledger_id', $request->credit_ledger)->where('type', 2)->sum('amount');
            $credit = Transaction::where('ledger_id', $request->credit_ledger)->where('type', 1)->sum('amount');
            $balance = $debit - $credit;
            $amount = $id->amount * $id->currency->rate;
            $difference = $balance - $amount;

            if ($truck->truck_type == 1) {
                $type = 'Semi';
            } else {
                $type = 'Pulling';
            }


            // Tazar::This need to be uncommented
            if ($difference <= 0 && (($type != $id->type) || ($id->type != "All"))) {
                return redirect()->back()->with('error', 'Insufficient Balance');
            } else {

                // if () {
                //     # code...
                // }

                $trip = Trip::where('allocation_id', $id->allocation_id)->first();
                $currency = Currency::where('id', $id->currency_id)->first();
                $amount = $id->amount * $currency->rate;
                // dd($amount);
                $payment = new AllocationCostPayment();
                $payment->amount = $amount;
                $payment->allocation_id = $id->allocation_id;
                $payment->truck_id = $truck->id;
                $payment->cost_id = $id->id;
                $payment->paid_date = Carbon::now()->format('Y-m-d');
                $payment->paid_by = Auth::user()->id;

                $cost = AllocationCost::find($id->id);
                if ($cost->quantity > 0) {
                    $routeCost = RouteCost::where('name', $cost->name)->first();
                    $fuelCost = FuelCost::where('id', $routeCost->cost_id)->first();
                    $expense_ledger = Ledger::where('id', $fuelCost->ledger_id)->first();

                    $fuel_ledger = Ledger::where('code', 7000)->where('tag', NULL)->first();
                    $fuelTranscation = new Transaction();
                    $fuelTranscation->ledger_id = $fuel_ledger->id;
                    $fuelTranscation->amount = $amount;
                    $fuelTranscation->description = 'Paid ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->ref_no;
                    $fuelTranscation->type = 2;
                    $fuelTranscation->save();
                } else {
                    $expense_ledger = Ledger::where('code', $cost->account_code)->first();
                }


                $truck = Truck::where('id', $request->truck_id)->first();

                $credit_ledger = $request->credit_ledger;
                $debit_ledger = $expense_ledger->id;

                // Start of Transaction
                $creditTranscation = new Transaction();
                $creditTranscation->ledger_id = $credit_ledger;
                $creditTranscation->amount = $amount;
                $creditTranscation->description = 'Paid ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->ref_no;
                $creditTranscation->type = 1;
                $creditTranscation->process = 'Allocation Cost Payment';
                $creditTranscation->category = $truck->plate_number;
                $creditTranscation->process_id = $cost->id;
                $creditTranscation->save();

                $debitTranscation = new Transaction();
                $debitTranscation->ledger_id = $debit_ledger;
                $debitTranscation->amount = $amount;
                $debitTranscation->process = 'Allocation Cost Payment';
                $debitTranscation->process_id = $cost->id;
                $debitTranscation->category = $truck->plate_number;
                $debitTranscation->description = 'Paid ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->ref_no;
                if ($cost->quantity > 0) {
                    $debitTranscation->type = 1;
                } else {
                    $debitTranscation->type = 2;
                }
                $debitTranscation->save();
                // end of Transactionx


                // Start of Tax
                if ($id->vat == '1') {

                    // start of Tax Transaction
                    $tax_ledger = Ledger::where('code', '2200')->first()->id;
                    $tax_amount = ($id->amount * $id->currency->rate) * (18 / 100);
                    $taxTranscation = new Transaction();

                    $taxTranscation->ledger_id = $tax_ledger;
                    $taxTranscation->category = $truck->plate_number;
                    $taxTranscation->amount = $tax_amount;
                    $taxTranscation->description = 'Paid VAT from Expense ' . $cost->name . ' of Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->ref_no;
                    $taxTranscation->type = 1;
                    $taxTranscation->process = 'Allocation Cost Payment';
                    $taxTranscation->process_id = $cost->id;
                    $taxTranscation->save();
                    // End of Tax Transaction
                }
                // End of Tax
                // dd( $expense_ledger);
                $payment->save();

                $id->status = 3;
                $id->update();
            }
            //End of balance check

        }



        // For Email Notification
        $trip = Trip::where('id', $request->trip_id)->first();
        $employees = User::where('position', $trip->allocation->user->position)->get();

        $email_data = array(
            'subject' => $truck->plate_number . ' Expense payment in ' . $trip->ref_no . ' Trip',
            'view' => 'emails.trips.expense-notification',
            'trip' => $trip,
            'truck' => $truck,
        );
        // $job = (new \App\Jobs\SendEmail($email_data, $employees));
        // dispatch($job);

        // dd('done');
        return back()->with('msg', 'Tip Expenses were Paid Successfully !');
    }


    public function bulk_truck_cost_payment(Request $request)
    {


        if ($request->input('selectedRows') == null) {
            return back()->with('error', 'Please Select Cost to Pay');
        }
        if ($request->input('selectedRows1') == null) {
            return back()->with('error', 'Please Select Trucks to Pay');
        }
        $selectedRows = $request->input('selectedRows');
        $selectedRows1 = $request->input('selectedRows1');
        $tripID = Trip::where('id', $request->trip_id)->first();
        foreach ($selectedRows1 as $truckId) {
            $truck = Truck::where('id', $truckId)->first();
            if ($truck->truck_type == 1) {
                $type = 'Semi';
            } else {
                $type = 'Pulling';
            }
            //  $request->all();
            // foreach ($request->moreFields as $key => $value) {
            foreach ($selectedRows as $rowId) {

                $id = AllocationCost::find($rowId);

                // if ($id->type==$type || $id->type=='All')
                // {

                // Checking  if the payment has sufficient balance
                $debit = Transaction::where('ledger_id', $request->credit_ledger)->where('type', 2)->sum('amount');
                $credit = Transaction::where('ledger_id', $request->credit_ledger)->where('type', 1)->sum('amount');
                $balance = $debit - $credit;
                $amount = $request->amount * $id->currency->rate;
                $difference = $balance - $amount;

                if ($difference <= 0) {
                    return redirect()->back()->with('error', 'Insufficient Balance');
                } else {


                    //End of balance check


                    $paid = AllocationCostPayment::where('cost_id', $id->id)->where('truck_id', $truck->id)->where(
                        'allocation_id',
                        $id->allocation_id
                    )->first();
                    if ($paid == NULL) {
                        if ($difference <= 0) {
                            return redirect()->back()->with('error', 'Insufficient Balance');
                        } else {
                            $trip = Trip::where('allocation_id', $id->allocation_id)->first();
                            // $trip->state=3;
                            // $trip->update();
                            $currency = Currency::where('id', $id->currency_id)->first();
                            $amount = $id->amount * $currency->rate;
                            // dd($amount);
                            $payment = new AllocationCostPayment();
                            $payment->amount = $amount;
                            $payment->allocation_id = $id->allocation_id;
                            $payment->truck_id = $truck->id;
                            $payment->cost_id = $id->id;
                            $payment->paid_date = Carbon::now()->format('Y-m-d');
                            $payment->paid_by = Auth::user()->id;

                            $cost = AllocationCost::find($id->id);
                            if ($cost->quantity > 0) {
                                $routeCost = RouteCost::where('name', $cost->name)->first();
                                $fuelCost = FuelCost::where('id', $routeCost->cost_id)->first();
                                $expense_ledger = Ledger::where('id', $fuelCost->ledger_id)->first();

                                $fuel_ledger = Ledger::where('code', 7000)->where('tag', NULL)->first();
                                $fuelTranscation = new Transaction();
                                $fuelTranscation->ledger_id = $fuel_ledger->id;
                                $fuelTranscation->amount = $amount;
                                $fuelTranscation->description = 'Paid ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->ref_no;
                                $fuelTranscation->type = 2;
                                $fuelTranscation->save();
                                $cost->real_amount = ($cost->amount * $cost->quantity) * $cost->currency->rate;
                            } else {
                                $expense_ledger = Ledger::where('code', $cost->account_code)->first();
                                $cost->real_amount = ($cost->amount) * $cost->currency->rate;
                            }
                            $cost->update();

                            $truck = Truck::where('id', $truckId)->first();

                            $credit_ledger = $request->credit_ledger;
                            $debit_ledger = $expense_ledger->id;

                            // Start of Transaction
                            $creditTranscation = new Transaction();
                            $creditTranscation->ledger_id = $credit_ledger;
                            $creditTranscation->amount = $amount;
                            $creditTranscation->description = 'Paid ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->ref_no;
                            $creditTranscation->type = 1;
                            $creditTranscation->process = 'Truck Cost Payment';
                            $creditTranscation->category = $truck->plate_number;
                            $creditTranscation->process_id = $cost->id;
                            $creditTranscation->save();

                            // start of Debit Transaction
                            $debitTranscation = new Transaction();
                            $debitTranscation->ledger_id = $debit_ledger;
                            $debitTranscation->amount = $amount;
                            $debitTranscation->description = 'Paid ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->ref_no;
                            if ($cost->quantity > 0) {
                                $debitTranscation->type = 1;
                            } else {
                                $debitTranscation->type = 2;
                            }
                            $debitTranscation->process = 'Truck Cost Payment';
                            $debitTranscation->category = $truck->plate_number;
                            $debitTranscation->process_id = $cost->id;
                            $debitTranscation->save();
                            // end of Transactionx


                            // Start of Tax
                            if ($id->vat == '1') {

                                // start of Tax Transaction
                                $tax_ledger = Ledger::where('code', '2200')->first()->id;
                                $tax_amount = ($id->amount * $id->currency->rate) * (18 / 100);
                                $taxTranscation = new Transaction();

                                $taxTranscation->ledger_id = $tax_ledger;
                                $taxTranscation->amount = $tax_amount;
                                $taxTranscation->category = $truck->plate_number;
                                $taxTranscation->description = 'Paid VAT from Expense ' . $cost->name . ' of Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->ref_no;
                                $taxTranscation->type = 1;
                                $taxTranscation->save();
                                // End of Tax Transaction
                            }
                            // End of Tax


                            $payment->save();
                        }
                    }
                }
            }


            // For Email Notification
            $trip = Trip::where('id', $request->trip_id)->first();
            // dd($trip->allocation->user->position_id);
            $employees = User::where('position', $trip->allocation->user->position_id)->get();

            // foreach ($employees as $item) {

            // $fullname = $item->name;
            $email_data = array(
                'subject' => $truck->plate_number . ' Expense payment in ' . $trip->ref_no . ' Trip',
                'view' => 'emails.trips.expense-notification',
                // 'email' => $item->email,
                // 'email' => 'baltazar.christian@cits.co.tz',
                'trip' => $trip,
                'truck' => $truck,
                // 'full_name' => $fullname,
            );
            //     Notification::route('mail', $email_data['email'])->notify(new EmailRequests($email_data));
            // }


            // $job = (new \App\Jobs\SendEmail($email_data, $employees));
            // dispatch($job);
            // // dd('done');

        }

        // For Transaction Charges
        if ($request->transaction_charges > 0) {
            $credit_ledger = $request->credit_ledger;
            $transaction_ledger = Ledger::where('client_code', 8026)->first();

            //Payment Ledger
            $chargesTransaction = new Transaction();
            $chargesTransaction->ledger_id = $credit_ledger;
            $chargesTransaction->amount = $request->transaction_charges;
            $chargesTransaction->description = 'Paid Transaction Charges in Bulk Trip Expense for Trip:' . $tripID->allocation->ref_no;
            $chargesTransaction->type = 1;
            $chargesTransaction->process = 'Bulk Trip Expense Paymentt';
            $chargesTransaction->category = 'Truck Expenses';
            $chargesTransaction->process_id = 1;
            $chargesTransaction->created_by = Auth::user()->id;
            $chargesTransaction->save();

            //Transaction Ledger
            $chargesTransaction = new Transaction();
            $chargesTransaction->ledger_id = $transaction_ledger->id;
            $chargesTransaction->amount = $request->transaction_charges;
            $chargesTransaction->description = 'Paid Transaction Charges in Bulk Trip Expense for Trip:' . $tripID->allocation->ref_no;
            $chargesTransaction->type = 2;
            $chargesTransaction->process = 'Bulk Trip Expense Payment';
            $chargesTransaction->category = 'Truck Expenses';
            $chargesTransaction->process_id = 1;
            $chargesTransaction->created_by = Auth::user()->id;
            $chargesTransaction->save();
        }
        // End of Transaction Charges


        // return back()->with('msg', 'Tip Expenses were Paid Successfully !');
        return redirect()->route('flex.tripDetails', $tripID->allocation_id)->with('msg', 'Trip Expenses were Paid Successfully !');
    }
}