<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\Truck;
// use App\Http\Controllers\Controller;
use App\Models\FuelCost;
use Dotenv\Parser\Lexer;
use App\Models\OffBudget;
use App\Models\RouteCost;
use App\Models\TruckCost;
use App\Models\Allocation;
use App\Models\Retirement;
use App\Models\FuelService;
use App\Models\Settings\Tax;
use Illuminate\Http\Request;
use App\Models\AllocationCost;
use App\Models\Store\Supplier;
use GuzzleHttp\Promise\Create;
use App\Models\TruckAllocation;
use App\Models\DriverAssignment;
use App\Models\TruckCostPayment;
use App\Models\RetirementRequest;
use App\Models\Settings\Currency;
use App\Models\AdministrationCost;
use App\Models\Account\Transaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Account\LedgerAccount;
use App\Models\AllocationCostPayment;
use App\Models\Store\ServicePurchase;
use App\Models\Store\ServicePurchaseItem;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreFuelServiceRequest;
use App\Http\Requests\UpdateFuelServiceRequest;

class FuelServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function save_lpo(Request $request)
    {
        // dd($request->all());
        $validate = Validator::make($request->all(), [
            'service_purchase_order_no' => 'required',
            'supplier_id' => 'required',
            'currency_symbol' => 'required',
            'subject' => 'required',
            'moreFields.*.service_name' => 'required',
            'moreFields.*.price' => 'required|numeric',
            'moreFields.*.quantity' => 'required|numeric',
            'moreFields.*.tax_rate' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->errors()->all(),
            ]);
        } else {

            if (empty($request->moreFields)) {
                return response()->json([
                    'status' => 401,
                    'errors' => 'Please Enter atleat one service',
                ]);
            } else {
                // Split Currency Symbol and Rate

                $dataCurrency = explode('--', $request->currency_symbol);

                // dd($dataCurrency);
                $currency_symbol  = $dataCurrency[0];
                $currency_rate = $dataCurrency[1];
                // End
                if ($request->type == 1) {
                    $id = AllocationCost::find($request->allocation_id);
                    // For Allocation Cost Payment

                    $truck_allocation = TruckAllocation::where('truck_id', $request->truck_id)->where('allocation_id', $id->allocation_id)->first();
                } else {

                    $id = TruckCost::find($request->allocation_id);
                    $truck_alloc = TruckAllocation::where('truck_id', $request->truck_id)->where('allocation_id', $id->allocation->allocation_id)->first();
                }
                $truck = Truck::where('id', $request->truck_id)->first();

                $new_subject = $request->subject . '-' . $id->name;
                $supplier = Supplier::where('id', $request->supplier_id)->first();
                $today = Carbon::now();
                $new_description = $truck->plate_number . '-' . strtoupper($truck_alloc->driver->fname) . ' ' . strtoupper($truck_alloc->driver->mname) . ' ' . strtoupper($truck_alloc->driver->lname) . '-DIESEL';

                $due_date = date('Y-m-d H:i:s', strtotime($today . ' + ' . $supplier->credit_term . ' days'));
                // Save in Service Purchase Table
                $service_purchase = new ServicePurchase();
                $service_purchase->service_purchase_prefix = $request->service_purchase_prefix;
                $service_purchase->service_purchase_order_no = $request->service_purchase_order_no;
                $service_purchase->supplier_id = $request->supplier_id;
                $service_purchase->subject = $new_subject;
                $service_purchase->tax_amount = $request->subtotal_tax;
                $service_purchase->currency_symbol = $currency_symbol;
                $service_purchase->currency_rate = $currency_rate;
                $service_purchase->subtotal = $request->subtotal;
                $service_purchase->total = $request->total_tax;
                $service_purchase->description = $new_description;
                $service_purchase->service_purchase_date = Carbon::now();
                $service_purchase->due_date = $due_date;
                $service_purchase->order_status = 'pending';
                $service_purchase->description = $request->description;
                $service_purchase->status = 1;
                $service_purchase->tag = 1;
                $service_purchase->procurement_level_status = 1;
                $service_purchase->workshop_request_id = null;
                $service_purchase->created_by = Auth::user()->id;
                $service_purchase->save();




                $amount = $request->subtotal * $currency_rate;
                if ($request->type == 1) {
                    $payment = new AllocationCostPayment();
                } else {
                    $payment = new TruckCostPayment();
                }


                $payment->amount = $amount;
                if ($request->type == 1) {
                    $payment->allocation_id = $id->allocation_id;
                } else {
                    $payment->cost_id = $id->allocation_id;
                }
                $payment->truck_id = $truck->id;
                $payment->cost_id = $id->id;
                $payment->paid_date = Carbon::now()->format('Y-m-d');
                $payment->paid_by = Auth::user()->id;

                $payment->save();

                if ($service_purchase) {
                    // Save in Purchase Items Table
                    foreach ($request->moreFields as $key => $value) {
                        $service_purchaseItems = new ServicePurchaseItem();
                        $service_purchaseItems->service_purchase_id = $service_purchase->id;
                        $service_purchaseItems->service_name = $value['service_name'];
                        $service_purchaseItems->tax_rate = $value['tax_rate'];
                        $service_purchaseItems->price = $value['price'];
                        $service_purchaseItems->quantity = $value['quantity'];
                        $service_purchaseItems->total = $value['total'];
                        $service_purchaseItems->save();

                        $id->amount = $value['price'];
                        $id->quantity = $value['quantity'];
                        $id->real_amount = ($value['price'] * $value['quantity']) * $id->currency->rate;
                        $id->update();
                        // $currency_rate=Currency::where('id',$request->currency_id)->first()->rate;

                        // Start of Transaction
                        // $debit_ledger_id = LedgerAccount::where('code', '7000')->first()->id;
                        // $credit_ledger_id = LedgerAccount::where('supplier_id', $request->supplier_id)->first()->id;
                        // $amount = $service_purchaseItems->total * $currency_rate;
                        // $narration = $new_subject;

                        // For Debit Entry
                        // $debitTransaction = new Transaction();
                        // $debitTransaction->ledger_id = $debit_ledger_id;
                        // $debitTransaction->amount = $amount;
                        // $debitTransaction->description = $narration;
                        // $debitTransaction->type = 2;
                        // $debitTransaction->Save();



                        // For Credit Entry
                        // $creditTransaction = new Transaction();
                        // $creditTransaction->ledger_id = $credit_ledger_id;
                        // $creditTransaction->amount = $amount;
                        // $creditTransaction->type = 1;
                        // $creditTransaction->description = $narration;
                        // $creditTransaction->Save();

                        // End of Transaction

                        // Start of Tax
                        if ($id->vat == '1') {

                            if ($request->type == 1) {
                                $description =  'Paid VAT from Expense ' . $id->name . ' of Truck ' . $truck->plate_number . ' in Trip : ' . $id->allocation->ref_no;
                            } else {

                                $allocation = Allocation::where('id', $id->allocation->allocation_id)->first();
                                $description =  'Paid VAT from Expense ' . $id->name . ' of Truck ' . $truck->plate_number . ' in Trip : ' . $allocation->ref_no;
                            }

                            // start of Tax Transaction
                            $tax_ledger =  LedgerAccount::where('code', '2200')->first()->id;
                            // $tax_amount =  ($id->amount * $id->currency->rate) * (18 / 100);
                            // $taxTranscation = new Transaction();

                            // $taxTranscation->ledger_id = $tax_ledger;
                            // $taxTranscation->amount = $request->total_tax;
                            // $taxTranscation->description =$description;
                            // $taxTranscation->type =  1;
                            // $taxTranscation->save();
                            // End of Tax Transaction
                        }
                        // End of Tax

                    }
                    return response()->json([
                        'status' => 200,
                        'route_purchase' => route('flex.procurementtripTruck', $request->truck_allocation),
                    ]);
                } else {
                    return response()->json([
                        'status' => 500,
                        'msg' => 'Data Not Inserted',
                    ]);
                }
            }
        }
    }


    // For Out of Budget Fuel LPO
    public function save_offbudget_lpo(Request $request)
    {
        // dd($request->all());
        $validate = Validator::make($request->all(), [
            'service_purchase_order_no' => 'required',
            'supplier_id' => 'required',
            'currency_symbol' => 'required',
            'subject' => 'required',
            'moreFields.*.service_name' => 'required',
            'moreFields.*.price' => 'required|numeric',
            'moreFields.*.quantity' => 'required|numeric',
            'moreFields.*.tax_rate' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->errors()->all(),
            ]);
        } else {

            if (empty($request->moreFields)) {
                return response()->json([
                    'status' => 401,
                    'errors' => 'Please Enter atleat one service',
                ]);
            } else {
                // Split Currency Symbol and Rate

                $dataCurrency = explode('--', $request->currency_symbol);

                // dd($dataCurrency);
                $currency_symbol  = $dataCurrency[0];
                $currency_rate = $dataCurrency[1];
                // End

                $id = OffBudget::find($request->allocation_id);
                $id->status = 3;
                $id->rate = $id->currency->rate;
                $id->real_amount = $request->total_tax;

                // For Allocation Cost Payment

                $truck = Truck::where('id', $request->truck_id)->first();
                $new_subject = $request->subject . '-' . $id->trips->ref_no;
                $supplier = Supplier::where('id', $request->supplier_id)->first();
                $today = Carbon::now();
                $new_description = $truck->plate_number . '-' . $request->description;

                $due_date = date('Y-m-d H:i:s', strtotime($today . ' + ' . $supplier->credit_term . ' days'));
                // Save in Service Purchase Table
                $service_purchase = new ServicePurchase();
                $service_purchase->service_purchase_prefix = $request->service_purchase_prefix;
                $service_purchase->service_purchase_order_no = $request->service_purchase_order_no;
                $service_purchase->supplier_id = $request->supplier_id;
                $service_purchase->subject = $new_subject;
                $service_purchase->tax_amount = $request->subtotal_tax;
                $service_purchase->currency_symbol = $currency_symbol;
                $service_purchase->currency_rate = $currency_rate;
                $service_purchase->subtotal = $request->subtotal;
                $service_purchase->total = $request->total_tax;
                $service_purchase->description = $new_description;
                $service_purchase->service_purchase_date = Carbon::now();
                $service_purchase->due_date = $due_date;
                $service_purchase->order_status = 'pending';
                $service_purchase->description = $request->description;
                $service_purchase->offbudget_id = $request->oob_id;
                $service_purchase->status = 1;
                $service_purchase->tag = 1;
                $service_purchase->procurement_level_status = 1;
                $service_purchase->workshop_request_id = null;
                $service_purchase->created_by = Auth::user()->id;
                $service_purchase->save();



                $id->update();


                if ($service_purchase) {
                    // Save in Purchase Items Table
                    foreach ($request->moreFields as $key => $value) {
                        $service_purchaseItems = new ServicePurchaseItem();
                        $service_purchaseItems->service_purchase_id = $service_purchase->id;
                        $service_purchaseItems->service_name = $value['service_name'];
                        $service_purchaseItems->tax_rate = $value['tax_rate'];
                        $service_purchaseItems->price = $value['price'];
                        $service_purchaseItems->quantity = $value['quantity'];
                        $service_purchaseItems->total = $value['total'];
                        $service_purchaseItems->save();
                    }
                    return response()->json([
                        'status' => 200,
                        'route_purchase' => route('flex.finance-offbudget'),
                    ]);
                } else {
                    return response()->json([
                        'status' => 500,
                        'msg' => 'Data Not Inserted',
                    ]);
                }
            }
        }
    }

    // For Administration Expense LPO
    public function save_administration_lpo(Request $request)
    {
        // dd($request->all());
        $validate = Validator::make($request->all(), [
            // 'service_purchase_order_no' => 'required',
            // 'supplier_id' => 'required',
            // 'currency_symbol' => 'required',
            // 'subject' => 'required',
            // 'moreFields.*.service_name' => 'required',
            // 'moreFields.*.price' => 'required|numeric',
            // 'moreFields.*.quantity' => 'required|numeric',
            // 'moreFields.*.tax_rate' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->errors()->all(),
            ]);
        } else {

            if (empty($request->moreFields)) {
                return response()->json([
                    'status' => 401,
                    'errors' => 'Please Enter atleat one service',
                ]);
            } else {
                // Split Currency Symbol and Rate

                $dataCurrency = explode('--', $request->currency_symbol);

                // dd($dataCurrency);
                $currency_symbol  = $dataCurrency[0];
                $currency_rate = $dataCurrency[1];
                // End

                $id = AdministrationCost::find($request->cost_id);
                $id->status = 3;
                $id->rate = $id->currency->rate;
                $id->real_amount = $request->total_tax;

                // For Allocation Cost Payment

                // $truck = Truck::where('id', $request->truck_id)->first();
                $new_subject = $request->subject;
                $supplier = Supplier::where('id', $request->supplier_id)->first();
                $today = Carbon::now();
                // $new_description = $truck->plate_number . '-' . $request->description;
                $new_description = $request->description;

                $due_date = date('Y-m-d H:i:s', strtotime($today . ' + ' . $supplier->credit_term . ' days'));
                // Save in Service Purchase Table
                $service_purchase = new ServicePurchase();
                $service_purchase->service_purchase_prefix = $request->service_purchase_prefix;
                $service_purchase->service_purchase_order_no = $request->service_purchase_order_no;
                $service_purchase->supplier_id = $request->supplier_id;
                $service_purchase->subject = $new_subject;
                $service_purchase->tax_amount = $request->subtotal_tax;
                $service_purchase->currency_symbol = $currency_symbol;
                $service_purchase->currency_rate = $currency_rate;
                $service_purchase->subtotal = $request->subtotal;
                $service_purchase->total = $request->total_tax;
                $service_purchase->description = $new_description;
                $service_purchase->service_purchase_date = Carbon::now();
                $service_purchase->due_date = $due_date;
                $service_purchase->order_status = 'pending';
                $service_purchase->description = $request->description;
                $service_purchase->admin_id = $request->oob_id;
                $service_purchase->status = 1;
                $service_purchase->tag = 1;
                $service_purchase->procurement_level_status = 1;
                $service_purchase->workshop_request_id = null;
                $service_purchase->created_by = Auth::user()->id;
                $service_purchase->save();



                $id->update();


                if ($service_purchase) {
                    // Save in Purchase Items Table
                    foreach ($request->moreFields as $key => $value) {
                        $service_purchaseItems = new ServicePurchaseItem();
                        $service_purchaseItems->service_purchase_id = $service_purchase->id;
                        $service_purchaseItems->service_name = $value['service_name'];
                        $service_purchaseItems->tax_rate = $value['tax_rate'];
                        $service_purchaseItems->price = $value['price'];
                        $service_purchaseItems->quantity = $value['quantity'];
                        $service_purchaseItems->total = $value['total'];
                        $service_purchaseItems->save();
                    }
                    return response()->json([
                        'status' => 200,
                        'route_purchase' => route('admistration-expenses.index'),
                    ]);
                } else {
                    return response()->json([
                        'status' => 500,
                        'msg' => 'Data Not Inserted',
                    ]);
                }
            }
        }
    }


    // For Retirement Expense LPO
    public function save_retirement_lpo(Request $request)
    {
        // dd($request->all());
        $validate = Validator::make($request->all(), [
            // 'service_purchase_order_no' => 'required',
            // 'supplier_id' => 'required',
            // 'currency_symbol' => 'required',
            // 'subject' => 'required',
            // 'moreFields.*.service_name' => 'required',
            // 'moreFields.*.price' => 'required|numeric',
            // 'moreFields.*.quantity' => 'required|numeric',
            // 'moreFields.*.tax_rate' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->errors()->all(),
            ]);
        } else {


            if (empty($request->moreFields)) {
                return response()->json([
                    'status' => 401,
                    'errors' => 'Please Enter atleat one service',
                ]);
            } else {
                // Split Currency Symbol and Rate

                $dataCurrency = explode('--', $request->currency_symbol);

                // dd($dataCurrency);
                $currency_symbol  = $dataCurrency[0];
                $currency_rate = $dataCurrency[1];
                // End

                $id = RetirementRequest::find($request->cost_id);
                $id->status = 3;
                // $id->rate = $id->currency->rate;
                // $id->real_amount=$request->total_tax;



                // For Allocation Cost Payment

                // $truck = Truck::where('id', $request->truck_id)->first();
                $new_subject = $request->subject;
                $supplier = Supplier::where('id', $request->supplier_id)->first();
                $today = Carbon::now();
                // $new_description = $truck->plate_number . '-' . $request->description;
                $new_description = $request->description;

                $due_date = date('Y-m-d H:i:s', strtotime($today . ' + ' . $supplier->credit_term . ' days'));
                // Save in Service Purchase Table
                $service_purchase = new ServicePurchase();
                $service_purchase->service_purchase_prefix = $request->service_purchase_prefix;
                $service_purchase->service_purchase_order_no = $request->service_purchase_order_no;
                $service_purchase->supplier_id = $request->supplier_id;
                $service_purchase->subject = $new_subject;
                $service_purchase->tax_amount = $request->subtotal_tax;
                $service_purchase->currency_symbol = $currency_symbol;
                $service_purchase->currency_rate = $currency_rate;
                $service_purchase->subtotal = $request->subtotal;
                $service_purchase->total = $request->total_tax;
                $service_purchase->description = $new_description;
                $service_purchase->service_purchase_date = Carbon::now();
                $service_purchase->due_date = $due_date;
                $service_purchase->order_status = 'pending';
                $service_purchase->description = $request->description;
                $service_purchase->retirement_id = $id->id;
                $service_purchase->status = 1;
                $service_purchase->tag = 2;
                $service_purchase->procurement_level_status = 1;
                $service_purchase->workshop_request_id = null;
                $service_purchase->created_by = Auth::user()->id;
                $service_purchase->save();



                $id->update();
                $diver_ledger = LedgerAccount::where('client_code', 1102)->first();

                $retirement = RetirementRequest::find($request->cost_id);

                if ($retirement->deduction_amount > 0) {
                    // Driver Amount
                    $driverTranscation = new Transaction();
                    $driverTranscation->ledger_id = $diver_ledger->id;
                    $driverTranscation->amount = ($retirement->deduction_amount) * $retirement->currency->rate;
                    $driverTranscation->description = 'Paid Deduction Expense in Trip :' . $retirement->trip->ref_no . ' with Driver Deduction to ' . $retirement->driver->fname . ' ' . $retirement->driver->mname . ' ' . $retirement->driver->lname;
                    $driverTranscation->type =  2;
                    $driverTranscation->process =  'Deduction Expense';
                    $driverTranscation->process_id = $retirement->id;
                    $driverTranscation->created_by = Auth::user()->id;
                    $driverTranscation->save();
                }

                // end of Transaction
                // $retirement->amount=$request->total_tax;
                $retirement->update();

                if ($retirement->deduction_amount > 0) {
                    Retirement::create([
                        'driver_id' => $id->driver_id,
                        'currency_id' => $id->currency_id,
                        'amount' => $id->deduction_amount,
                        'reason' => $id->description,
                        'rate' => $id->currency->rate,
                        'request_id' => $id->id,
                        'real_amount' => ($id->deduction_amount * $id->currency->rate),
                        'date' => date('Y-m-d'),
                        'created_by' => $id->created_by
                    ]);
                }



                if ($service_purchase) {
                    // Save in Purchase Items Table
                    foreach ($request->moreFields as $key => $value) {
                        $service_purchaseItems = new ServicePurchaseItem();
                        $service_purchaseItems->service_purchase_id = $service_purchase->id;
                        $service_purchaseItems->service_name = $value['service_name'];
                        $service_purchaseItems->tax_rate = $value['tax_rate'];
                        $service_purchaseItems->price = $value['price'];
                        $service_purchaseItems->quantity = $value['quantity'];
                        $service_purchaseItems->total = $value['total'];
                        $service_purchaseItems->save();
                    }



                    return response()->json([
                        'status' => 200,
                        'route_purchase' => route('flex.procurement-driver-retirements'),
                    ]);
                } else {

                    return response()->json([
                        'status' => 500,
                        'msg' => 'Data Not Inserted',
                    ]);
                }
            }
        }
    }


    // For Bulk Fuel LPO
    public function generate_lpo(Request $request)
    {
        $cost = AllocationCost::where('id', $request->cost_id)->where('quantity', '>', 0)->first();
        // dd($cost);
        $allocation = Allocation::where('id', $request->allocation_id)->first();
        $id = RouteCost::where('id', $cost->route_cost->id)->first();
        $cost1 = FuelCost::where('id', $id->cost_id)->first();
        $supplierID = LedgerAccount::where('id', $cost1->ledger_id)->first();
        $supplier = Supplier::where('id', $supplierID->supplier_id)->first();

        $today = Carbon::now();

        $due_date = date('Y-m-d H:i:s', strtotime($today . ' + ' . $supplier->credit_term . ' days'));

        $data['latest_service_purchase'] = ServicePurchase::latest('created_at')->first();
        $data['allocation'] = $allocation;
        $data['supplier_ledger'] = $supplierID->id;
        $data['supplier'] = $supplier;
        $data['fuel_costs'] = $cost;
        $data['due_date'] = $due_date;
        $data['trucks'] = TruckAllocation::where('allocation_id', $allocation->id)->where('rescue_status', 0)->get();
        $data['currencies'] = Currency::latest()->get();
        $data['taxes'] = Tax::where('id', $allocation->tax_id)->latest()->get();

        return view('trips.fuel.create_lpo', $data);
    }


    // For Fuel Cost Detail
    //  for fetching departments positions
    public function getCostDetails($id = 0)
    {
        $data = AllocationCost::where('id', $id)->with('currency')->get();
        return response()->json($data);
    }


    //For Bulk Fuel LPO Store
    public function save_bulk_lpo(Request $request)
    {

        if ($request->input('selectedRows') == null) {
            return response()->json([
                'status' => 401,
                'errors' => 'Please Select Atleast One Truck',
            ]);
        } else {

            $cost = AllocationCost::where('id', $request->cost_id)->where('quantity', '>', 0)->first();
            $total_items = count($request->input('selectedRows'));
            $amount = $request->input('amount');
            // $total_amount=$total_items*$amount;
            $quantity = $request->input('quantity');
            $subtotal = $total_items * ($amount * $quantity);

            $total_quantity = $total_items * $quantity;
            $single_total = $amount;
            if ($cost->vat == 1) {
                $tax_amount = 0.18;
                $subtotal_tax = $subtotal * $tax_amount;
            } else {
                $subtotal_tax = 0;
            }
            $cost->quantity = $request->input('quantity');
            $cost->amount = $request->input('amount');
            $cost->real_amount = ($request->input('amount') * $request->input('quantity')) * $cost->currency->rate;
            $cost->update();

            $total_tax = $subtotal_tax + $subtotal;
            // dd( $request->subject);


            $dataCurrency = explode('--', $request->currency_symbol);

            // dd($dataCurrency);
            $currency_symbol  = $dataCurrency[0];
            $currency_rate = $dataCurrency[1];
            $new_description = 'This is a bulk purchase order for fuel  service.';

            $supplier = Supplier::where('id', $request->supplier_id)->first();
            $today = Carbon::now();

            $due_date = date('Y-m-d H:i:s', strtotime($today . ' + ' . $supplier->credit_term . ' days'));

            $service_purchase = ServicePurchase::create(
                [
                    'service_purchase_prefix' => $request->service_purchase_prefix,
                    'service_purchase_order_no' => $request->service_purchase_order_no,
                    'supplier_id' => $request->supplier_id,
                    'subject' => $request->subject,
                    'tax_amount' => $subtotal_tax,
                    'currency_symbol' => $currency_symbol,
                    'currency_rate' => $currency_rate,
                    'subtotal' => $subtotal,
                    'total' => $total_tax,
                    'description' => $new_description,
                    'service_purchase_date' => Carbon::now(),
                    'due_date' => $due_date,
                    'order_status' => 'pending',
                    'description' => $request->description,
                    'status' => 1,
                    'procurement_level_status' => 1,
                    'tag' => 1,
                    'workshop_request_id' => null,
                    'created_by' => Auth::user()->id,
                ]
            );



            $selectedRows = $request->input('selectedRows');

            foreach ($selectedRows as $truck) {

                $truck = Truck::where('id', $truck)->first();
                $truck_alloc = TruckAllocation::where('truck_id', $truck->id)->where('allocation_id', $cost->allocation_id)->first();

                $amount = $single_total;
                if ($request->type == 1) {
                    $payment = new AllocationCostPayment();
                } else {
                    $payment = new TruckCostPayment();
                }

                $new_subject =  $truck->plate_number . '-' . strtoupper($truck_alloc->driver->fname) . ' ' . strtoupper($truck_alloc->driver->mname) . ' ' . strtoupper($truck_alloc->driver->lname) . '-DIESEL';

                $payment->amount = $amount * $quantity;
                if ($request->type == 1) {
                    $payment->allocation_id = $cost->allocation_id;
                } else {
                    $payment->cost_id = $cost->allocation_id;
                }
                $payment->truck_id = $truck->id;
                $payment->cost_id = $cost->id;
                $payment->paid_date = Carbon::now()->format('Y-m-d');
                $payment->paid_by = Auth::user()->id;

                $payment->save();

                if ($service_purchase) {
                    // Save in Purchase Items Table

                    $service_purchaseItems = ServicePurchaseItem::create([
                        'service_purchase_id' => $service_purchase->id,
                        'service_name' => $truck->plate_number . '-' . strtoupper($truck_alloc->driver->fname) . ' ' . strtoupper($truck_alloc->driver->mname) . ' ' . strtoupper($truck_alloc->driver->lname) . '-DIESEL',
                        'tax_rate' => 0,
                        'price' => $amount,
                        'quantity' => $quantity,
                        'total' => $amount * $quantity,
                    ]);
                } else {
                    return response()->json([
                        'status' => 500,
                        'msg' => 'Data Not Inserted',
                    ]);
                }
            }
            return response()->json([
                'status' => 200,
                'route_purchase' => route('flex.tripDetails', $cost->allocation_id),
            ]);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFuelServiceRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FuelService $fuelService)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FuelService $fuelService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFuelServiceRequest $request, FuelService $fuelService)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FuelService $fuelService)
    {
        //
    }
}
