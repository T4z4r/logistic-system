<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use App\Models\RouteCost;
use App\Models\TruckCost;
use App\Models\Allocation;
use App\Models\CommonCost;
use App\Models\CurrencyLog;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\AllocationCost;
use Illuminate\Support\Carbon;
use App\Models\CurrencyLogItem;
use App\Models\TruckAllocation;
use App\Models\MobilicationCost;
use App\Models\MobilizationCost;
use App\Models\TruckCostPayment;
use App\Models\Currency;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Models\Ledger;
use App\Models\AllocationCostPayment;

class TripExpensePayment extends Controller
{
    //For All Trip Expenses

    public function index()
    {
        $data['costs'] = CommonCost::latest()->get();
        return view('trip_expenses.index', $data);
    }

    // For Single Expense

    public function show($id)
    {

        set_time_limit(600);
        ini_set('memory_limit', '5024M');
        // Set to a value that suits your needs

        $data['cost'] = CommonCost::where('id', $id)->first();

        $data['payment_methods'] = PaymentMethod::get();

        $resultsArray = [];

        $resultsArray1 = [];

        $route_costs = RouteCost::where('cost_id', $id)->where('quantity', NULL)->get();

        // Start Of Allocation Costs
        $i = 1;
        foreach ($route_costs as $route_cost) {
            $name = $data['cost']->name;
            $allocation_costs = AllocationCost::where('name', 'LIKE', '%' . $name . '%')->where('route_id', $route_cost->route_id)->get();
            foreach ($allocation_costs as $allocation_cost) {
                $truck_allocations = TruckAllocation::where('allocation_id', $allocation_cost->allocation_id)->get();
                foreach ($truck_allocations as $truck_allocation) {
                    $payment = AllocationCostPayment::where('truck_id', $truck_allocation->truck_id)->where('allocation_id', $allocation_cost->allocation_id)->where('cost_id', $allocation_cost->id)->first();
                    if ($payment == null) {

                        if ($allocation_cost->type == $truck_allocation->truck->type->name || $allocation_cost->type == 'All') {

                            $resultsArray[$i++] = [
                                'trip_number' => $allocation_cost->allocation->ref_no,
                                'truck_id' => $truck_allocation->truck->id,
                                'cost_id' => $allocation_cost->id,
                                'driver_name' => $truck_allocation->driver->fname . ' ' . $truck_allocation->driver->lname,
                                'amount' => $allocation_cost->amount,
                                'cost_type' => $allocation_cost->type,
                                'truck_type' => $truck_allocation->truck->type->name ?? 'null',
                                'quantity' => $allocation_cost->quantity,
                                'symbol' => $allocation_cost->currency->symbol,
                                'total_amount' => $allocation_cost->amount * $allocation_cost->currency->rate,
                                'plate_number' => $truck_allocation->truck->plate_number,
                                'cost_name' => $route_cost->name,
                            ];
                        }
                    }
                }
            }
        }

        $cost = $data['cost'];

        // $route_costs1 = RouteCost::where( 'cost_id', $id )->where( 'quantity', NULL )->get();

        $route_costs1 = MobilizationCost::where('cost_id', $id)->where('quantity', NULL)->get();

        // Start of Truck Costs
        $i = 1;
        foreach ($route_costs1 as $route_cost1) {

            $allocation_costs1 = TruckCost::
                // where( 'route_id', $route_cost1->route_id )->
                where('name', 'like', '%' . $route_cost1->name . '%')->get();

            if (count($allocation_costs1) > 0) {

                foreach ($allocation_costs1 as $allocation_cost1) {
                    $truck_allocations = TruckAllocation::where('id', $allocation_cost1->allocation_id)->get();
                    foreach ($truck_allocations as $truck_allocation) {
                        if ($allocation_cost1->truck_id == $truck_allocation->truck_id) {

                            $payment = TruckCostPayment::where('truck_id', $truck_allocation->truck_id)->where('cost_id', $allocation_cost1->id)->first();

                            if ($payment == null) {

                                $resultsArray1[$i++] = [
                                    'trip_number' => $allocation_cost1->allocation->allocation->ref_no,
                                    'truck_id' => $truck_allocation->truck->id,
                                    'cost_id' => $allocation_cost1->id,
                                    'driver_name' => $truck_allocation->driver->fname . ' ' . $truck_allocation->driver->lname,
                                    'amount' => $allocation_cost1->amount,
                                    'cost_type' => $allocation_cost1->type,
                                    'truck_type' => $truck_allocation->truck->type->name ?? 'null',
                                    'quantity' => $allocation_cost1->quantity,
                                    'symbol' => $allocation_cost1->currency->symbol,
                                    'total_amount' => $allocation_cost1->amount * $allocation_cost1->currency->rate,
                                    'plate_number' => $truck_allocation->truck->plate_number,
                                    'cost_name' => $allocation_cost1->name,
                                    'mobilization' => $allocation_cost1->mobilization,

                                ];
                            }
                        }
                    }
                }
            }
        }

        $data['allocation_costs'] = $resultsArray;
        $data['truck_costs'] = $resultsArray1;

        return view('trip_expenses.show', $data);
    }

    // For Payment History
    public function payment_history()
    {

        $data['paid_allocation_costs'] = AllocationCostPayment::latest()->take(300)->get();
        $data['paid_truck_costs'] = TruckCostPayment::latest()->take(200)->get();

        return view('trip_expenses.history', $data);
    }

    // For View Payment
    public function view_payment_history($id)
    {

        $data['payment'] = AllocationCostPayment::where('id', $id)->first();
        $data['transactions'] = Transaction::where('process', 'Allocation Cost Payment')->where('process_id', $data['payment']->cost_id)->get();

        return view('trip_expenses.view_payment_history', $data);
    }

    // For Delete Allocation Payment
    public function delete_allocation_history($id)
    {

        $payment = AllocationCostPayment::where('id', $id)->first();
        $truck = Truck::where('id', $payment->truck_id)->first();
        if ($payment != null) {
            $transactions = Transaction::where('process', 'Allocation Cost Payment')->where('process_id', $payment->cost_id)->where('category', $truck->plate_number)->get();
            foreach ($transactions as $transaction) {
                $transaction->delete();
            }

            $payment->delete();
        } else {
            return back()->with('error', 'Record Not Found');
        }
    }

    // For All Expenses
    public function all_expenses1()
    {

        set_time_limit(600);
        ini_set('memory_limit', '5024M');
        // Set to a value that suits your needs

        // $data[ 'cost' ] = CommonCost::lates()->first();

        $data['payment_methods'] = PaymentMethod::get();

        $resultsArray = [];

        $resultsArray1 = [];

        $route_costs = RouteCost::latest()->where('quantity', NULL)->get();

        // Start Of Allocation Costs
        $i = 1;
        foreach ($route_costs as $route_cost) {
            $name = $route_cost->name;
            $currentYear = date('Y');

            $allocation_costs = AllocationCost::where('name', 'LIKE', '%' . $name . '%')
                ->where('route_id', $route_cost->route_id)
                ->whereYear('created_at', $currentYear)
                ->latest()
                ->limit(35)
                ->get();

            // ->take( 150 )
            // ->get();

            foreach ($allocation_costs as $allocation_cost) {
                $truck_allocations = TruckAllocation::where('allocation_id', $allocation_cost->allocation_id)->get();
                foreach ($truck_allocations as $truck_allocation) {
                    $payment = AllocationCostPayment::where('truck_id', $truck_allocation->truck_id)->where('allocation_id', $allocation_cost->allocation_id)->where('cost_id', $allocation_cost->id)->first();
                    if ($payment == null) {

                        if ($allocation_cost->type == $truck_allocation->truck->type->name || $allocation_cost->type == 'All') {

                            $resultsArray[$i++] = [
                                'trip_number' => $allocation_cost->allocation->ref_no,
                                'truck_id' => $truck_allocation->truck->id,
                                'cost_id' => $allocation_cost->id,
                                'driver_name' => $truck_allocation->driver->fname . ' ' . $truck_allocation->driver->lname,
                                'amount' => $allocation_cost->amount,
                                'cost_type' => $allocation_cost->type,
                                'truck_type' => $truck_allocation->truck->type->name ?? 'null',
                                'quantity' => $allocation_cost->quantity,
                                'symbol' => $allocation_cost->currency->symbol,
                                'total_amount' => $allocation_cost->amount * $allocation_cost->currency->rate,
                                'plate_number' => $truck_allocation->truck->plate_number,
                                'cost_name' => $route_cost->name,
                            ];
                        }
                    }
                }
            }
        }

        // $cost = $resultsArray;

        // $route_costs1 = RouteCost::where( 'cost_id', $id )->where( 'quantity', NULL )->get();
        $currentYear = date('Y');

        $route_costs1 = MobilizationCost::latest()->where('quantity', NULL)->whereYear('created_at', $currentYear)
            ->limit(20);
        // ->get();
        // Start of Truck Costs
        $i = 1;
        foreach ($route_costs1 as $route_cost1) {

            $allocation_costs1 = TruckCost::
                // where( 'route_id', $route_cost1->route_id )->
                where('name', 'like', '%' . $route_cost1->name . '%')->get();

            if (count($allocation_costs1) > 0) {

                foreach ($allocation_costs1 as $allocation_cost1) {
                    $truck_allocations = TruckAllocation::where('id', $allocation_cost1->allocation_id)->get();
                    foreach ($truck_allocations as $truck_allocation) {
                        if ($allocation_cost1->truck_id == $truck_allocation->truck_id) {

                            $payment = TruckCostPayment::where('truck_id', $truck_allocation->truck_id)->where('cost_id', $allocation_cost1->id)->first();

                            if ($payment == null) {

                                $resultsArray1[$i++] = [
                                    'trip_number' => $allocation_cost1->allocation->allocation->ref_no,
                                    'truck_id' => $truck_allocation->truck->id,
                                    'cost_id' => $allocation_cost1->id,
                                    'driver_name' => $truck_allocation->driver->fname . ' ' . $truck_allocation->driver->lname,
                                    'amount' => $allocation_cost1->amount,
                                    'cost_type' => $allocation_cost1->type,
                                    'truck_type' => $truck_allocation->truck->type->name ?? 'null',
                                    'quantity' => $allocation_cost1->quantity,
                                    'symbol' => $allocation_cost1->currency->symbol,
                                    'total_amount' => $allocation_cost1->amount * $allocation_cost1->currency->rate,
                                    'plate_number' => $truck_allocation->truck->plate_number,
                                    'cost_name' => $allocation_cost1->name,
                                    'mobilization' => $allocation_cost1->mobilization,

                                ];
                            }
                        }
                    }
                }
            }
        }

        $data['allocation_costs'] = $resultsArray;
        $data['truck_costs'] = $resultsArray1;

        return view('trip_expenses.all', $data);
    }

    // For All Expenses
    public function all_expenses(Request $request)
    {
        // Get search query from the request
        $search = $request->input('search');

        $data['payment_methods'] = PaymentMethod::latest()->get();
        $resultsArray = [];
        $resultsArray1 = [];
        $currentYear = 2024;

        // $data['allocations'] = Allocation::where('status', 4)
        //     ->whereYear( 'created_at', $currentYear )
        //     ->latest()
        //     //  ->where( 'type', 2 )
        //     //  ->limit(40)
        //     ->paginate(2);

        // Fetch allocations with optional search filtering
        $data['allocations'] = Allocation::where('status', 4)
            ->latest()
            ->whereYear('created_at', '>=', $currentYear)->when($search, function ($query, $search) {
                // Modify the query to search in specific columns (e.g., driver name, trip, etc.)
                return $query->where('ref_no', 'like', "%{$search}%");
            })->paginate(1);  // Paginate the result


        $data['allocation_costs'] = $resultsArray;
        $data['truck_costs'] = $resultsArray1;

        return view('trip_expenses.all', $data);
    }

    // Start of Save Allocation Cost Payment
    public function save(Request $request)
    {
        if (empty($request->selectedRows)) {
            return back()->with('error', 'Please Select At least One Expense!');
        } else {
            $count = 0;

            // Begin transaction
            DB::beginTransaction();
            try {
                if ($request->payment_rate == 2) {

                    // creating variables for currencies
                    $tzs_id = $request->currency_id_1;
                    $usd_id = $request->currency_id_3;
                    $zk_id = $request->currency_id_2;
                    $tzs_rate = $request->currency_value_1;
                    $usd_rate = $request->currency_value_3;
                    $zk_rate = $request->currency_value_2;

                    // update currency rates
                    Currency::where('id', $tzs_id)->update(['corridor_rate' => $tzs_rate]);
                    Currency::where('id', $usd_id)->update(['corridor_rate' => $usd_rate]);
                    Currency::where('id', $zk_id)->update(['corridor_rate' => $zk_rate]);

                    // create new Currency Log
                    $currencyLog = new CurrencyLog();
                    $currencyLog->created_date = now();
                    $currencyLog->created_by = Auth::user()->id;
                    $currencyLog->save();

                    // Save new Currency Log Items
                    $currencies = Currency::latest()->get();
                    foreach ($currencies as $currency) {
                        $currencyLogItem = new CurrencyLogItem();
                        $currencyLogItem->currency_log_id = $currencyLog->id;
                        $currencyLogItem->currency_id = $currency->id;
                        $currencyLogItem->rate = $currency->rate;
                        $currencyLogItem->corridor_rate = $currency->corridor_rate;
                        $currencyLogItem->created_by = Auth::user()->id;
                        $currencyLogItem->save();
                    }
                } else {
                    $tzs_rate = 1;
                    $usd_rate = 1;
                    $zk_rate = 1;

                    // create new Currency Log
                    $currencyLog = new CurrencyLog();
                    $currencyLog->created_date = now();
                    $currencyLog->created_by = Auth::user()->id;
                    $currencyLog->save();

                    // Save new Currency Log Items
                    $currencies = Currency::latest()->get();
                    foreach ($currencies as $currency) {
                        $currencyLogItem = new CurrencyLogItem();
                        $currencyLogItem->currency_log_id = $currencyLog->id;
                        $currencyLogItem->currency_id = $currency->id;
                        $currencyLogItem->rate = $currency->rate;
                        $currencyLogItem->corridor_rate = $currency->rate;
                        $currencyLogItem->created_by = Auth::user()->id;
                        $currencyLogItem->save();
                    }
                    // For Flex  Currency Log
                    $currencyLog = CurrencyLog::latest()->first();
                    if ($currencyLog == null) {
                        DB::rollBack();
                        return back()->with('error', 'Sorry,There is no any currency Log !');
                    }
                }

                foreach ($request->input('selectedRows') as $key => $row) {
                    if (isset($row['check']) && $row['check'] == 'on') {
                        // For Updating Cost Values

                        // Fetching the selected cost details
                        $cost = AllocationCost::where('id', $row['cost_id'])->first();

                        // Declaring variables
                        $tzs_amount = 0;
                        $usd_amount = 0;
                        $corridor_amount = 0;
                        // Checking if its a corridor payment
                        if ($request->payment_rate == 2) {

                            //For Usd
                            if ($cost->currency_id == 3) {
                                // Converting the cost into usd using corridor rate
                                $usd_amount = $row['amount'];
                                $corridor_amount = ($row['amount'] + $row['transaction_charges'] + $row['service_charges'] + $row['vat_charges']) / $usd_rate;
                                $usd_transaction_charges = $row['transaction_charges'] * $usd_rate;
                                $usd_service_charges = $row['service_charges'] * $usd_rate;
                                $usd_vat_charges = $row['vat_charges'] * $usd_rate;

                                // converting the usd amount into flex tzs
                                $tzs_amount = $usd_amount * $cost->currency->rate;
                                $tzs_transaction_charges = $usd_transaction_charges * $cost->currency->rate;
                                $tzs_service_charges = $usd_service_charges * $cost->currency->rate;
                                $tzs_vat_charges = $usd_vat_charges * $cost->currency->rate;
                            }

                            // For Zambian Kwacha
                            if ($cost->currency_id == 2) {
                                // Converting the cost into usd using corridor rate
                                $usd_amount = $row['amount'];

                                $usd_transaction_charges = $row['transaction_charges'];
                                $usd_service_charges = $row['service_charges'];
                                $usd_vat_charges = $row['vat_charges'];

                                $corridor_amount = $row['amount'] + $row['transaction_charges'] + $row['service_charges'] + $row['vat_charges'];
                                // converting the usd amount into flex tzs
                                $tzs_amount = $usd_amount * $cost->currency->rate;
                                $tzs_transaction_charges = $usd_transaction_charges * $cost->currency->rate;
                                $tzs_service_charges = $usd_service_charges * $cost->currency->rate;
                                $tzs_vat_charges = $usd_vat_charges * $cost->currency->rate;
                            }

                            // For Tzs
                            if ($cost->currency_id == 1) {
                                // Converting the cost into usd using corridor rate
                                $usd_amount = $row['amount'];

                                $usd_transaction_charges = $row['transaction_charges'];
                                $usd_service_charges = $row['service_charges'];
                                $usd_vat_charges = $row['vat_charges'];

                                // converting the usd amount into flex tzs
                                $tzs_amount = $usd_amount * $cost->currency->rate;
                                $tzs_transaction_charges = $usd_transaction_charges * $cost->currency->rate;
                                $tzs_service_charges = $usd_service_charges * $cost->currency->rate;
                                $tzs_vat_charges = $usd_vat_charges * $cost->currency->rate;
                                $corridor_amount = (($row['amount'] + $row['transaction_charges']) / $usd_rate) / $usd_rate;
                            }
                        }
                        //  Else  Flex Payment
                        else {
                            $usd = Currency::where('id', 3)->first();
                            $usd_rate = $usd->rate;
                            $tzs_amount = $row['amount'] * $cost->currency->rate;
                            if ($cost->currency_id == 2) {
                                $corridor_amount = (($row['amount'] + $row['transaction_charges'] + $row['service_charges'] + $row['vat_charges']) * $cost->currency->rate) / $usd_rate;
                            } elseif ($cost->currency_id == 1) {

                                $corridor_amount = (($row['amount'] + $row['transaction_charges'] + $row['service_charges'] + $row['vat_charges']) / $usd_rate) / $usd_rate;
                            } else {
                                $corridor_amount = ($row['amount'] + $row['transaction_charges'] + $row['service_charges'] + $row['vat_charges']) / $usd_rate;
                            }

                            $corridor_rate = $usd->rate;
                            // For Charges
                            $tzs_transaction_charges = $row['transaction_charges'] * $cost->currency->rate;
                            $tzs_service_charges = $row['service_charges'] * $cost->currency->rate;
                            $tzs_vat_charges = $row['vat_charges'] * $cost->currency->rate;
                        }


                        $truck_id = $row['truck_id'];
                        $truck = Truck::where('id', $truck_id)->first();
                        $truck_alloc = TruckAllocation::where('truck_id', $truck->id)
                            ->where('allocation_id', $cost->allocation_id)
                            ->first();

                        $amount = $tzs_amount;




                        // Check Charges Ledgers
                        $transaction_ledger = Ledger::where('client_code', 8026)->first();
                        $service_ledger = Ledger::where('client_code', 7312)->first();
                        $vat_ledger = Ledger::where('client_code', 1902)->first();

                        if ($transaction_ledger == null) {
                            return back()->with('error', 'Transaction ledger not available CODE:8026!');
                        }
                        if ($service_ledger == null) {
                            return back()->with('error', 'Service ledger not available CODE:7312!');
                        }
                        if ($vat_ledger == null) {
                            return back()->with('error', 'VAT ledger not available CODE:1902!');
                        }

                        // Check balance
                        $debit = Transaction::where('ledger_id', $request->credit_ledger)
                            ->where('type', 2)->sum('amount');
                        $credit = Transaction::where('ledger_id', $request->credit_ledger)
                            ->where('type', 1)->sum('amount');
                        $balance = $debit - $credit;
                        $difference = $balance - $amount;

                        if ($difference < 0) {
                            DB::rollBack();
                            return redirect()->back()->with('error', 'Insufficient Balance');
                        }



                        $credit_ledger = $request->credit_ledger;
                        $debit_ledger = $cost->route_cost->cost->account->id ?? Ledger::where('code', $cost->account_code)->first()->id;

                        // Create Credit Transaction
                        $creditTransaction = new Transaction();
                        $creditTransaction->ledger_id = $credit_ledger;
                        $creditTransaction->amount = $amount + $tzs_transaction_charges + $tzs_service_charges + $tzs_vat_charges;
                        $creditTransaction->description = 'Paid ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->ref_no;
                        $creditTransaction->type = 1;
                        $creditTransaction->process = 'Allocation Cost Payment';
                        $creditTransaction->process_id = $cost->id;
                        $creditTransaction->category = $truck_alloc->truck->plate_number;
                        $creditTransaction->currency_log_id = $currencyLog->id;
                        $creditTransaction->corridor_amount = $corridor_amount;
                        $creditTransaction->corridor_rate = $usd_rate;
                        $creditTransaction->save();

                        // Create Debit Transaction
                        $debitTransaction = new Transaction();
                        $debitTransaction->ledger_id = $debit_ledger;
                        $debitTransaction->amount = $amount;
                        $debitTransaction->process = 'Allocation Cost Payment';
                        $debitTransaction->process_id = $cost->id;
                        $debitTransaction->description = 'Paid ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->ref_no;
                        $debitTransaction->type = 2;
                        $debitTransaction->category = $truck_alloc->truck->plate_number;
                        $debitTransaction->currency_log_id = $currencyLog->id;
                        $debitTransaction->save();

                        // Transaction charges, service charges, and VAT transactions
                        if ($row['transaction_charges'] > 0) {
                            $chargesTransaction = new Transaction();
                            $chargesTransaction->ledger_id = $transaction_ledger->id;
                            $chargesTransaction->amount = $tzs_transaction_charges;
                            $chargesTransaction->description = 'Paid Transaction Charges in the payment of ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->ref_no;
                            $chargesTransaction->type = 2;
                            $chargesTransaction->process = 'Allocation Cost Payment';
                            $chargesTransaction->category = $truck_alloc->truck->plate_number;
                            $chargesTransaction->process_id = $cost->id;
                            $chargesTransaction->created_by = Auth::user()->id;
                            $chargesTransaction->currency_log_id = $currencyLog->id;
                            $chargesTransaction->save();
                        }

                        if ($row['service_charges'] > 0) {
                            $serviceChargesTransaction = new Transaction();
                            $serviceChargesTransaction->ledger_id = $service_ledger->id;
                            $serviceChargesTransaction->amount = $tzs_service_charges;
                            $serviceChargesTransaction->description = 'Paid Service Charges in the payment of ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->ref_no;
                            $serviceChargesTransaction->type = 2;
                            $serviceChargesTransaction->process = 'Allocation Cost Payment';
                            $serviceChargesTransaction->category = $truck_alloc->truck->plate_number;
                            $serviceChargesTransaction->process_id = $cost->id;
                            $serviceChargesTransaction->created_by = Auth::user()->id;
                            $serviceChargesTransaction->currency_log_id = $currencyLog->id;
                            $serviceChargesTransaction->save();
                        }

                        if ($row['vat_charges'] > 0) {
                            $vatChargesTransaction = new Transaction();
                            $vatChargesTransaction->ledger_id = $vat_ledger->id;
                            $vatChargesTransaction->amount = $tzs_vat_charges;
                            $vatChargesTransaction->description = 'Paid VAT Charges in the payment of ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->ref_no;
                            $vatChargesTransaction->type = 2;
                            $vatChargesTransaction->process = 'Allocation Cost Payment';
                            $vatChargesTransaction->category = $truck_alloc->truck->plate_number;
                            $vatChargesTransaction->process_id = 1;
                            $vatChargesTransaction->created_by = Auth::user()->id;
                            $vatChargesTransaction->currency_log_id = $currencyLog->id;
                            $vatChargesTransaction->save();
                        }

                        // Save Payment
                        $payment = new AllocationCostPayment();
                        $payment->amount = $row['amount'] * $cost->currency->rate;
                        $payment->allocation_id = $truck_alloc->allocation_id;
                        $payment->truck_id = $truck->id;
                        $payment->cost_id = $cost->id;
                        $payment->currency_log_id = $currencyLog->id;
                        $payment->transaction_charges = $tzs_transaction_charges;
                        $payment->service_charges = $tzs_service_charges;
                        $payment->vat_charges = $tzs_vat_charges;
                        $payment->paid_date = Carbon::now()->format('Y-m-d');
                        $payment->real_paid_amount = $amount;
                        $payment->paid_amount = $row['amount'] + $row['transaction_charges'] + $row['service_charges'] + $row['vat_charges'];
                        $payment->paid_by = Auth::user()->id;
                        $payment->save();

                        // Update cost status
                        $total_payment = AllocationCostPayment::where('allocation_id', $payment->allocation_id)
                            ->where('truck_id', $payment->truck_id)
                            ->where('cost_id', $payment->cost_id)
                            ->sum('amount');
                        $converted_payment = $total_payment / $cost->rate;

                        $cost->status = ($converted_payment >= $cost->amount) ? 3 : 2;
                        $cost->rate = $cost->currency->rate;
                        $cost->real_amount = $cost->amount * $cost->currency->rate;
                        $cost->update();

                        $count++;
                    }
                }

                if ($count == 0) {
                    DB::rollBack();
                    return back()->with('error', 'Please Select At least One Truck!');
                }

                // Commit the transaction
                DB::commit();
                return back()->with('success', 'Allocation Cost Paid Successfully!');
            } catch (\Exception $e) {
                // Rollback the transaction if something fails
                DB::rollBack();
                return back()->with('error', 'An error occurred: ' . $e->getMessage());
            }
        }
    }

    // End of Save Allocation Cost Payment


    // Start of  Truck Cost Payment
    public function save_truck_expense(Request $request)
    {
        if (empty($request->selectedRows)) {
            return back()->with('error', 'Please Select Atleast One Expense!');
        } else {
            $count = 0;
            $transaction_ledger = Ledger::where('client_code', 8026)->first();

            if ($request->payment_rate == 2) {

                // creating variables for currencies
                $tzs_id = $request->currency_id_1;
                $usd_id = $request->currency_id_3;
                $zk_id = $request->currency_id_2;
                $tzs_rate = $request->currency_value_1;
                $usd_rate = $request->currency_value_3;
                $zk_rate = $request->currency_value_2;

                // Update Currency Rates
                $tzs_rate = ($tzs_rate == 0) ? 1 : $tzs_rate;
                $usd_rate = ($usd_rate == 0) ? 1 : $usd_rate;
                $zk_rate = ($zk_rate == 0) ? 1 : $zk_rate;

                // update currency rates
                Currency::where('id', $tzs_id)->update(['corridor_rate' => $tzs_rate]);
                Currency::where('id', $usd_id)->update(['corridor_rate' => $usd_rate]);
                Currency::where('id', $zk_id)->update(['corridor_rate' => $zk_rate]);

                // create new Currency Log

                $currencyLog = new CurrencyLog();
                $currencyLog->created_date = now();
                $currencyLog->created_by = Auth::user()->id;
                $currencyLog->save();

                // Save new Currency Log Items
                $currencies = Currency::latest()->get();
                foreach ($currencies as $currency) {
                    $currencyLogItem = new CurrencyLogItem();
                    $currencyLogItem->currency_log_id = $currencyLog->id;
                    $currencyLogItem->currency_id = $currency->id;
                    $currencyLogItem->rate = $currency->rate;
                    $currencyLogItem->corridor_rate = $currency->corridor_rate;
                    $currencyLogItem->created_by = Auth::user()->id;
                    $currencyLogItem->save();
                }
            } else {

                // For Flex  Currency Log
                $currencyLog = CurrencyLog::latest()->first();
                if ($currencyLog == null) {
                    DB::rollBack();
                    return back()->with('error', 'Sorry,There is no any currency Log !');
                }
            }
            // Start DB transaction
            DB::beginTransaction();
            try {
                foreach ($request->input('selectedRows') as $key => $row) {
                    if (isset($row['check']) && $row['check'] == 'on') {

                        // For Updating Cost Values
                        $cost = TruckCost::where('id', $row['cost_id'])->first();

                        if ($cost == null) {
                            // Rollback and return error
                            DB::rollBack();
                            return redirect()->back()->with('error', 'Cost Not Found !');
                        }





                        // Declaring variables
                        // Declaring variables
                        $tzs_amount = 0;
                        $usd_amount = 0;
                        $corridor_amount = 0;
                        // Checking if its a corridor payment
                        if ($request->payment_rate == 2) {


                            //For Usd
                            if ($cost->currency_id == 3) {


                                // Converting the cost into usd using corridor rate
                                $usd_amount = $row['amount'];
                                $corridor_amount = ($row['amount'] + $row['transaction_charges'] + $row['service_charges'] + $row['vat_charges']) / $usd_rate;
                                $usd_transaction_charges = $row['transaction_charges'];
                                $usd_service_charges = $row['service_charges'];
                                $usd_vat_charges = $row['vat_charges'];


                                // converting the usd amount into flex tzs
                                $tzs_amount = $usd_amount * $cost->currency->rate;
                                $tzs_transaction_charges = $usd_transaction_charges * $cost->currency->rate;
                                $tzs_service_charges = $usd_service_charges * $cost->currency->rate;
                                $tzs_vat_charges = $usd_vat_charges * $cost->currency->rate;
                            }

                            // For Zambian Kwacha
                            if ($cost->currency_id == 2) {


                                // Converting the cost into usd using corridor rate
                                $usd_amount = $row['amount'];

                                $usd_transaction_charges = $row['transaction_charges'];
                                $usd_service_charges = $row['service_charges'];
                                $usd_vat_charges = $row['vat_charges'];

                                $corridor_amount = $row['amount'] + $row['transaction_charges'] + $row['service_charges'] + $row['vat_charges'];
                                // converting the usd amount into flex tzs
                                $tzs_amount = $usd_amount * $cost->currency->rate;
                                $tzs_transaction_charges = $usd_transaction_charges * $cost->currency->rate;
                                $tzs_service_charges = $usd_service_charges * $cost->currency->rate;
                                $tzs_vat_charges = $usd_vat_charges * $cost->currency->rate;
                            }

                            // For Tzs
                            if ($cost->currency_id == 1) {


                                // Converting the cost into usd using corridor rate
                                $usd_amount = $row['amount'];

                                $usd_transaction_charges = $row['transaction_charges'];
                                $usd_service_charges = $row['service_charges'];
                                $usd_vat_charges = $row['vat_charges'];

                                // converting the usd amount into flex tzs
                                $tzs_amount = $usd_amount * $cost->currency->rate;
                                $tzs_transaction_charges = $usd_transaction_charges * $cost->currency->rate;
                                $tzs_service_charges = $usd_service_charges * $cost->currency->rate;
                                $tzs_vat_charges = $usd_vat_charges * $cost->currency->rate;
                                $corridor_amount = $row['amount'] + $row['transaction_charges'] + $tzs_rate;
                            }
                        }
                        //  Else  Flex Payment
                        else {
                            $usd = Currency::where('id', 3)->first();
                            $usd_rate = $usd->rate;
                            $tzs_amount = $row['amount'] * $cost->currency->rate;
                            if ($cost->currency_id == 2) {
                                $corridor_amount = (($row['amount'] + $row['transaction_charges'] + $row['service_charges'] + $row['vat_charges']) * $cost->currency->rate) / $usd_rate;
                            } elseif ($cost->currency_id == 1) {

                                $corridor_amount = (($row['amount'] + $row['transaction_charges'] + $row['service_charges'] + $row['vat_charges']) / $usd_rate) / $usd_rate;
                            } else {
                                $corridor_amount = ($row['amount'] + $row['transaction_charges'] + $row['service_charges'] + $row['vat_charges']) / $usd_rate;
                            }

                            $corridor_rate = $usd->rate;
                            // For Charges
                            $tzs_transaction_charges = $row['transaction_charges'] * $cost->currency->rate;
                            $tzs_service_charges = $row['service_charges'] * $cost->currency->rate;
                            $tzs_vat_charges = $row['vat_charges'] * $cost->currency->rate;
                        }





                        $truck_id = $row['truck_id'];
                        $truck = Truck::where('id', $truck_id)->first();
                        $truck_alloc = TruckAllocation::where('truck_id', $truck->id)->where('allocation_id', $cost->allocation->allocation_id)->first();

                        $amount = $tzs_amount;

                        // Checking if the payment has sufficient balance
                        $debit = Transaction::where('ledger_id', $request->credit_ledger)->where('type', 2)->sum('amount');
                        $credit = Transaction::where('ledger_id', $request->credit_ledger)->where('type', 1)->sum('amount');
                        $balance = $debit - $credit;
                        $difference = $balance - $amount;

                        if ($difference < 0) {
                            // Rollback and return error
                            DB::rollBack();
                            return redirect()->back()->with('error', 'Insufficient Balance');
                        }


                        $credit_ledger = $request->credit_ledger;

                        if ($cost->route_cost != null) {
                            $debit_ledger = $cost->route_cost?->cost?->account->id ?? null;
                            if ($debit_ledger == null) {
                                $debit_ledger_account = Ledger::where('code', $cost->account_code)->first();
                                $debit_ledger = $debit_ledger_account->id;
                                if ($debit_ledger == null) {
                                    // Rollback and return error
                                    DB::rollBack();
                                    return redirect()->back()->with('error', 'Sorry!, the ledger was not found!');
                                }
                            }
                        } else {
                            $debit_ledger = $cost->mobilization_cost->cost->account->id ?? null;
                            if ($debit_ledger == null) {
                                $debit_ledger_account = Ledger::where('code', $cost->account_code)->first();
                                $debit_ledger = $debit_ledger_account->id;

                                if ($debit_ledger == null) {
                                    // Rollback and return error
                                    DB::rollBack();
                                    return redirect()->back()->with('error', 'Sorry!, the ledger was not found!');
                                }
                            }
                        }



                        // Start of Transaction
                        $creditTransaction = new Transaction();
                        $creditTransaction->ledger_id = $credit_ledger;
                        $creditTransaction->amount = $amount + $tzs_transaction_charges + $tzs_service_charges + $tzs_vat_charges;
                        $creditTransaction->description = 'Paid ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->allocation->ref_no;
                        $creditTransaction->type = 1;
                        $creditTransaction->process = 'Truck Cost Payment';
                        $creditTransaction->process_id = $cost->id;
                        $creditTransaction->category = $truck->plate_number;
                        $creditTransaction->currency_log_id = $currencyLog->id;
                        $creditTransaction->corridor_amount = $corridor_amount;
                        $creditTransaction->corridor_rate = $usd_rate;
                        $creditTransaction->save();

                        $debitTransaction = new Transaction();
                        $debitTransaction->ledger_id = $debit_ledger;
                        $debitTransaction->amount = $amount;
                        $debitTransaction->process = 'Truck Cost Payment';
                        $debitTransaction->process_id = $cost->id;
                        $debitTransaction->description = 'Paid ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->allocation->ref_no;
                        $debitTransaction->type = 2;
                        $debitTransaction->category = $truck->plate_number;
                        $debitTransaction->currency_log_id = $currencyLog->id;
                        $debitTransaction->corridor_amount = $corridor_amount;
                        $debitTransaction->corridor_rate = $usd_rate;
                        $debitTransaction->save();
                        // End of Transaction

                        // Transaction Ledger
                        if ($row['transaction_charges'] > 0) {
                            $chargesTransaction = new Transaction();
                            $chargesTransaction->ledger_id = $transaction_ledger->id;
                            $chargesTransaction->amount = $tzs_transaction_charges;
                            $chargesTransaction->description = 'Paid Transaction Charges in the payment of ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->allocation->ref_no;
                            $chargesTransaction->type = 2;
                            $chargesTransaction->process = 'Truck Cost Payment';
                            $chargesTransaction->category = $truck->plate_number;
                            $chargesTransaction->process_id = $cost->id;
                            $chargesTransaction->currency_log_id = $currencyLog->id;
                            $chargesTransaction->created_by = Auth::user()->id;
                            $chargesTransaction->save();
                        }

                        // Service Ledger
                        if ($row['service_charges'] > 0) {
                            $service_ledger = Ledger::where('client_code', 7312)->first();
                            $chargesTransaction = new Transaction();
                            $chargesTransaction->ledger_id = $service_ledger->id;
                            $chargesTransaction->amount = $tzs_service_charges;
                            $chargesTransaction->description = 'Paid Service Charges in the payment of ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->allocation->ref_no;
                            $chargesTransaction->type = 2;
                            $chargesTransaction->process = 'Truck Cost Payment';
                            $chargesTransaction->category = $truck->plate_number;
                            $chargesTransaction->process_id = $cost->id;
                            $chargesTransaction->created_by = Auth::user()->id;
                            $chargesTransaction->currency_log_id = $currencyLog->id;
                            $chargesTransaction->save();
                        }

                        // VAT Ledger
                        if ($row['vat_charges'] > 0) {
                            $vat_ledger = Ledger::where('client_code', 1902)->first();
                            $chargesTransaction = new Transaction();
                            $chargesTransaction->ledger_id = $vat_ledger->id;
                            $chargesTransaction->amount = $tzs_vat_charges;
                            $chargesTransaction->description = 'Paid VAT Charges in the payment of ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->allocation->ref_no;
                            $chargesTransaction->type = 2;
                            $chargesTransaction->process = 'Truck Cost Payment';
                            $chargesTransaction->category = $truck->plate_number;
                            $chargesTransaction->process_id = $cost->id;
                            $chargesTransaction->created_by = Auth::user()->id;
                            $chargesTransaction->currency_log_id = $currencyLog->id;
                            $chargesTransaction->save();
                        }




                        // For Saving Payment
                        $payment = new TruckCostPayment();
                        $payment->amount = $amount;
                        $payment->truck_id = $truck->id;
                        $payment->cost_id = $cost->id;
                        $payment->currency_log_id = $currencyLog->id;
                        $payment->transaction_charges = $tzs_transaction_charges;
                        $payment->service_charges = $tzs_service_charges;
                        $payment->vat_charges = $tzs_vat_charges;
                        $payment->paid_date = Carbon::now()->format('Y-m-d');
                        $payment->paid_by = Auth::user()->id;
                        $payment->save();

                        $total_payment = TruckCostPayment::where('truck_id', $payment->truck_id)
                            ->where('cost_id', $payment->cost_id)
                            ->sum('amount');

                        $converted_payment = $total_payment / $cost->rate;


                        if ($converted_payment >= $cost->amount) {

                            $cost->status = 3;
                        } else {

                            $cost->status = 2;
                        }
                        $cost->update();


                        $count++;
                    }
                }

                if ($count == 0) {
                    DB::rollBack();
                    return back()->with('error', 'Please Select Atleast One Truck !');
                }

                // Commit transaction
                DB::commit();
                return back()->with('msg', 'Truck Cost Paid Successfully!');
            } catch (\Exception $e) {
                // Rollback the transaction on error
                DB::rollBack();
                return back()->with('error', 'Error occurred: ' . $e->getMessage());
            }
        }
    }
    // end of Truck Cost Payment



    // For Driver Mileages
    public function all_mileages()
    {

        $data['payment_methods'] = PaymentMethod::latest()->get();
        $resultsArray = [];
        $resultsArray1 = [];
        $currentYear = date('Y');

        $data['allocations'] = Allocation::where('status', 4)
            // ->whereYear( 'created_at', $currentYear )
            ->latest()
            //  ->where( 'type', 2 )
            //  ->limit( 20 )
            ->get();
        $data['allocation_costs'] = $resultsArray;
        $data['truck_costs'] = $resultsArray1;

        return view('trip_expenses.millages', $data);
    }


    public function save_advance(Request $request)
    {

        if (empty($request->selectedRows)) {
            return back()->with('error', 'Please Select Atleast One Expense!');
        } else {

            $count = 0;

            foreach ($request->input('selectedRows') as $key => $row) {

                if (isset($row['check']) && $row['check'] == 'on') {

                    // For Updating Cost Values
                    $cost = AllocationCost::where('id', $row['cost_id'])->first();
                    // $cost->amount = $row['amount'];
                    // $cost->real_amount = ($row['amount']) * $cost->currency->rate;
                    // $cost->update();

                    $truck_id = $row['truck_id'];
                    $truck = Truck::where('id', $truck_id)->first();
                    $truck_alloc = TruckAllocation::where('truck_id', $truck->id)->where('allocation_id', $cost->allocation_id)->first();

                    $amount = $row['amount'] * $cost->currency->rate;

                    // Checking  if the payment has sufficient balance
                    $debit = Transaction::where('ledger_id', $request->credit_ledger)->where('type', 2)->sum('amount');
                    $credit = Transaction::where('ledger_id', $request->credit_ledger)->where('type', 1)->sum('amount');
                    $balance = $debit - $credit;
                    $difference = $balance - $amount;

                    if ($difference < 0) {
                        return redirect()->back()->with('error', 'Insufficient Balance');
                    }
                    //End of balance check

                    $credit_ledger = $request->credit_ledger;
                    $debit_ledger = $cost->route_cost->cost->account->id;

                    // Start of Transaction
                    $creditTranscation = new Transaction();
                    $creditTranscation->ledger_id = $credit_ledger;
                    $creditTranscation->amount = $amount + $row['transaction_charges'] * $cost->currency->rate;
                    $creditTranscation->description = 'Paid ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->ref_no;
                    $creditTranscation->type = 1;
                    $creditTranscation->process = 'Advance Allocation Cost Payment';
                    $creditTranscation->process_id = $cost->id;
                    $creditTranscation->category = $truck_alloc->truck->plate_number;
                    $creditTranscation->save();

                    $debitTranscation = new Transaction();
                    $debitTranscation->ledger_id = $debit_ledger;
                    $debitTranscation->amount = $amount;
                    $debitTranscation->process = 'Advance Allocation Cost Payment';
                    $debitTranscation->process_id = $cost->id;
                    $debitTranscation->description = 'Paid ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->ref_no;
                    $debitTranscation->type = 2;
                    $debitTranscation->category = $truck_alloc->truck->plate_number;
                    $debitTranscation->save();
                    // end of Transaction

                    //Transaction Ledger
                    if ($row['transaction_charges'] > 0) {
                        $transaction_ledger = Ledger::where('client_code', 8026)->first();

                        $chargesTransaction = new Transaction();
                        $chargesTransaction->ledger_id = $transaction_ledger->id;
                        $chargesTransaction->amount = $row['transaction_charges'] * $cost->currency->rate;
                        $chargesTransaction->description = 'Paid Transaction Charges in the payment of ' . $cost->name . ' to Truck ' . $truck->plate_number . ' in Trip : ' . $cost->allocation->ref_no;
                        $chargesTransaction->type = 2;
                        $chargesTransaction->process = 'Advance Allocation Cost Payment';
                        $chargesTransaction->category = $truck_alloc->truck->plate_number;
                        $chargesTransaction->process_id = 1;
                        $chargesTransaction->created_by = Auth::user()->id;
                        $chargesTransaction->save();
                    }

                    // For Saving Payment
                    $payment = new AllocationCostPayment();
                    $payment->amount = $amount;
                    $payment->allocation_id = $truck_alloc->allocation_id;
                    $payment->truck_id = $truck->id;
                    $payment->cost_id = $cost->id;
                    $payment->transaction_charges = $row['transaction_charges'] * $cost->currency->rate;
                    $payment->paid_date = Carbon::now()->format('Y-m-d');
                    $payment->paid_by = Auth::user()->id;
                    $payment->save();


                    $total_payment = AllocationCostPayment::where('allocation_id', $payment->allocation_id)
                        ->where('truck_id', $payment->truck_id)
                        ->where('cost_id', $payment->cost_id)
                        ->sum('amount');
                    $converted_payment = $total_payment / $cost->rate;
                    if ($converted_payment == $cost->amount) {
                        $cost->status = 3;
                    } else {
                        $cost->status = 2;
                    }
                    $cost->update();
                    $count = $count + 1;
                }
            }
        }

        if ($count == 0) {
            return back()->with('error', 'Please Select Atleast One Truck !');
        } else {
            return back()->with('msg', 'Allocation Cost Paid Successifully!');
        }

        // return response()->json( [
        //     'status' => 200,
        //     'route_purchase' => route( 'flex.all_trip_expenses1' ),
        // ] );
    }
}