<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Route;
use App\Models\Truck;
use App\Models\Approval;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Position;
use App\Models\RouteCost;
use App\Models\Allocation;
use App\Models\CargoNature;
use App\Models\CurrencyLog;
use App\Models\PaymentMode;
use Illuminate\Http\Request;
use App\Models\ApprovalLevel;
use App\Models\AllocationCost;
use App\Models\CurrencyLogItem;
use App\Models\TruckAllocation;
use App\Models\DriverAssignment;
use App\Models\TrailerAssignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AllocationController extends Controller
{
    public function index()
    {
        $allocations = Allocation::with(['customer', 'route', 'createdBy', 'nature', 'mode'])
            ->paginate(10);
        return view('allocations.index', compact('allocations'));
    }

    public function active()
    {
        $allocations = Allocation::with(['customer', 'route', 'createdBy', 'cargoNature', 'paymentMode'])
            ->where('status', 1)
            ->paginate(10);
        return view('allocations.index', compact('allocations'));
    }

    public function inactive()
    {
        $allocations = Allocation::with(['customer', 'route', 'createdBy', 'cargoNature', 'paymentMode'])
            ->where('status', 0)
            ->paginate(10);
        return view('allocations.index', compact('allocations'));
    }

    public function create()
    {
        $data['customers'] = Customer::latest()->get();
        $data['routes'] = Route::latest()->where('status', 1)->get();
        $data['nature'] = CargoNature::latest()->where('status', 0)->get();
        $data['mode'] = PaymentMode::latest()->where('status', 0)->get();
        $data['currency'] = Currency::latest()->where('status', 1)->get();
        return view('allocations.create', $data);
    }


    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validate input with custom error messages
            $validator = Validator::make($request->all(), [
                'customer_id' => 'required|integer|exists:customers,id',
                'cargo' => 'required|string|max:255',
                'cargo_ref' => 'required|string|max:255',
                'cargo_nature' => 'required|integer|exists:cargo_natures,id',
                'amount' => 'required|numeric|min:0',
                'payment_mode' => 'required|integer|exists:payment_modes,id',
                'payment_currency' => 'required|integer|exists:currencies,id',
                'container' => 'required|in:Yes,No',
                'type' => 'required|in:1,2',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'route_id' => 'required|integer|exists:routes,id',
                'loading_point' => 'required|string|max:255',
                'offloading_point' => 'required|string|max:255',
                'quantity' => 'required|numeric|min:0',
                'unit' => 'required|in:Ton,Container',
                'container_type' => 'required|in:Null,SOC,COC',
                'clearance' => 'required|in:Yes,No',
                'dimensions' => 'required|string|max:255',
            ], [
                'customer_id.required' => 'Please select a customer.',
                'cargo.required' => 'Cargo name is required.',
                'cargo_ref.required' => 'Cargo reference number is required.',
                'cargo_nature.required' => 'Please select the cargo nature.',
                'amount.required' => 'Payment amount is required.',
                'payment_mode.required' => 'Please select a payment mode.',
                'payment_currency.required' => 'Please select a payment currency.',
                'container.required' => 'Please specify if a container is used.',
                'type.required' => 'Please select the trip type.',
                'start_date.required' => 'Start date is required.',
                'end_date.after_or_equal' => 'End date must be on or after the start date.',
                'route_id.required' => 'Please select a route.',
                'loading_point.required' => 'Loading point is required.',
                'offloading_point.required' => 'Offloading point is required.',
                'quantity.required' => 'Cargo quantity is required.',
                'unit.required' => 'Please select a metric unit.',
                'container_type.required' => 'Please select a container type.',
                'clearance.required' => 'Please specify if clearance is required.',
                'dimensions.required' => 'Cargo dimensions are required.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Create allocation
            $allocation = new Allocation();
            $allocation->customer_id = $request->customer_id;
            $allocation->cargo = $request->cargo;
            $allocation->cargo_ref = $request->cargo_ref;
            $allocation->cargo_nature = $request->cargo_nature;
            $allocation->amount = $request->amount;
            $allocation->quantity = $request->quantity;
            $allocation->payment_mode = $request->payment_mode;
            $allocation->payment_currency = $request->payment_currency;
            $allocation->start_date = $request->start_date;
            $allocation->end_date = $request->end_date;
            $allocation->route_id = $request->route_id;
            $allocation->loading_site = $request->loading_point;
            $allocation->offloading_site = $request->offloading_point;
            $allocation->unit = $request->unit;
            $allocation->type = $request->type;
            $allocation->container = $request->container;
            $allocation->container_type = $request->container_type;
            $allocation->clearance = $request->clearance;
            $allocation->dimensions = $request->dimensions;
            $allocation->created_by = Auth::user()->id;
            $allocation->save();

            // Generate reference number
            $prefix = $allocation->type == 1 ? 'GL' : 'BL';
            $allocation->ref_no = sprintf(
                '%s-%s%s%s-%d',
                $prefix,
                $allocation->customer?->abbreviation ?? 'Trip',
                date('y', strtotime($allocation->created_at)),
                date('m', strtotime($allocation->created_at)),
                $allocation->id
            );
            $allocation->save();

            // Create allocation costs
            $routeCosts = RouteCost::where('route_id', $request->route_id)->get();
            foreach ($routeCosts as $item) {
                $cost = new AllocationCost();
                $cost->allocation_id = $allocation->id;
                $cost->name = $item->name;
                $cost->vat = $item->vat;
                $cost->return = $item->return;
                $cost->editable = $item->editable;
                $cost->type = $item->type;
                $currency = Currency::find($item->currency_id);
                $real_amount = $item->amount * $currency->rate;
                $cost->amount = $item->amount;
                $cost->real_amount = $item->quantity > 0 ? $real_amount * $item->quantity : $real_amount;
                $cost->quantity = $item->quantity > 0 ? $item->quantity : null;
                $cost->currency_id = $item->currency_id;
                $cost->account_code = $item->account_code;
                $cost->rate = $currency->rate;
                $cost->route_id = $item->route_id;
                $cost->status = 0;
                $cost->created_by = Auth::user()->id;
                $cost->save();
            }

            // Log activity
            // SystemLogHelper::logSystemActivity(
            //     'Allocation Creation',
            //     Auth::user()->id,
            //     Auth::user()->fname . ' ' . Auth::user()->lname . ' has created an Allocation #' . $allocation->ref_no
            // );

            DB::commit();

            return redirect()->route('flex.truck-allocation', base64_encode($allocation->id))
                ->with('success', 'Allocation created successfully');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            Log::error('Error saving allocation: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->with('error', 'An error occurred while saving the allocation. Please try again.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $allocation = Allocation::findOrFail($id);
        $customers = Customer::all();
        $routes = Route::all();
        $users = User::all();
        $cargoNatures = CargoNature::all();
        $paymentModes = PaymentMode::all();
        return view('allocations.edit', compact('allocation', 'customers', 'routes', 'users', 'cargoNatures', 'paymentModes'));
    }

    public function update(Request $request, $id)
    {
        $allocation = Allocation::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'ref_no' => 'nullable|string|max:255',
            'Customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'cargo' => 'required|string|max:255',
            'cargo_ref' => 'required|string|max:255',
            'estimated_pay' => 'required|numeric|min:0',
            'cargo_nature' => 'required',
            'payment_mode' => 'required|exists:payment_modes,id',
            'loading_site' => 'nullable|string|max:255',
            'offloading_site' => 'nullable|string|max:255',
            'clearance' => 'required|string|in:Yes,No',
            'container' => 'required|string|in:Yes,No',
            'container_type' => 'nullable|string|max:255',
            'dimensions' => 'nullable|string|max:255',
            'payment_currency' => 'required|string|max:255',
            'rate' => 'nullable|numeric|min:0',
            'real_amount' => 'nullable|numeric|min:0',
            'route_id' => 'required|exists:routes,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'unit' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
            'approval_status' => 'required|integer|in:0,1,2',
            'type' => 'required|integer|in:1,2',
            'state' => 'nullable|string|max:255',
            'goingload_id' => 'nullable|integer',
            'approver_id' => 'required|integer|min:0',
            'disapprover_id' => 'required|integer|min:0',
            'usd_income' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $allocation->update(array_merge(
            $request->only([
                'ref_no',
                'Customer_id',
                'amount',
                'quantity',
                'cargo',
                'cargo_ref',
                'estimated_pay',
                'cargo_nature',
                'payment_mode_id',
                'loading_site',
                'offloading_site',
                'clearance',
                'container',
                'container_type',
                'dimensions',
                'payment_currency',
                'rate',
                'real_amount',
                'route_id',
                'start_date',
                'end_date',
                'unit',
                'status',
                'approval_status',
                'type',
                'state',
                'goingload_id',
                'approver_id',
                'disapprover_id',
                'usd_income',
            ]),
            ['created_by' => Auth::id()]
        ));

        return redirect()->route('allocations.list')->with('success', 'Allocation updated successfully.');
    }

    public function destroy($id)
    {
        $allocation = Allocation::findOrFail($id);
        $allocation->delete();
        return redirect()->route('allocations.list')->with('success', 'Allocation deleted successfully.');
    }


    // For Truck Allocation Page
    public function truck_allocation($id)
    {
        $id = base64_decode($id);
        $uid = Auth::user()->position;
        $position = Position::where('id', $uid)->first();
        $process = Approval::firstOrCreate(
            ['process_name' => 'Allocation Approval'],
            [
                'levels' => 0,
                'escallation' => false, // or true, depending on your logic
                'escallation_time' => null
            ]
        );


        $allocation = Allocation::where('id', $id)->first();
        $latest_status = $allocation->approval_status;
        $current = ApprovalLevel::where('level_name', $latest_status)->where('approval_id', $process->id)->first();
        if ($current) {

            $data['current_person'] = $current->roles->name;
        } else {
            if ($latest_status <= 0) {
                $data['current_person'] = 'Assistant Fleet Controller';
            } else {
                $data['current_person'] = 'Initiated Trip : ' . $allocation->ref_no;
            }
        }
        $data['level'] = ApprovalLevel::where('role_id', $uid)->where('approval_id', $process->id)->first();
        $data['check'] = 'Approved By ' . $position;
        $data['allocation'] = Allocation::find($id);
        $data['routes'] = Route::latest()->where('status', 1)->get();
        $data['nature'] = CargoNature::latest()->where('status', 0)->get();
        $data['mode'] = PaymentMode::latest()->where('status', 0)->get();
        $data['currency'] = Currency::latest()->where('status', 1)->get();
        $msg = "Allocation details save successfully, please choose the trucks !";


        return view('trips.allocations.truck_allocation', $data)->with('msg', $msg);
    }


    // For Save Bulk Truck Allocation
    public function add_bulk_trucks(Request $request)
    {

        if ($request->input('selectedRows') == null) {
            return back()->with('error', 'Please Select Truck To Allocate');
        }
        $selectedRows = $request->input('selectedRows');

        foreach ($selectedRows as $rowId) {

            $truck = Truck::find($rowId);
            $driver = DriverAssignment::where('truck_id', $truck->id)->first();
            $trailer = TrailerAssignment::where('truck_id', $truck->id)->get();

            $allocation = new TruckAllocation();
            $allocation->allocation_id = $request->allocation_id;
            $id = Allocation::where('id', $request->allocation_id)->first();

            if ($id->payment_mode == 1) {

                $current_amount = $id->estimated_pay;
                $new_amount = $current_amount + $id->amount;
                $new_real_amount = $new_amount * $id->currency->rate;
                $allocation->income = $id->amount * $id->currency->rate;;
                $allocation->usd_income = $id->amount;
                $id->usd_income = $new_amount;
                $id->estimated_pay = $new_amount;
                $id->real_amount = $new_real_amount;
                $id->rate = $id->currency->rate;

                $id->update();
            } else {
                // $capacity = $truck->capacity;
                $capacity = 0;
                foreach ($trailer as $item) {
                    $capacity += $item->trailer->capacity;
                }

                $current_amount = $id->estimated_pay;
                $new_amount = $current_amount + $capacity * $id->amount;
                $new_real_amount = $new_amount * $id->currency->rate;
                $id->estimated_pay = $new_amount;
                $id->real_amount = $new_real_amount;
                $id->usd_income = $new_amount;
                $allocation->income = $capacity * $id->amount * $id->currency->rate;
                $allocation->usd_income = $capacity * $id->amount;
                $id->rate = $id->currency->rate;

                $id->update();
            }
            $costs = RouteCost::where('route_id', $id->route_id)->sum('real_amount');
            $trailer1 = TrailerAssignment::where('truck_id', $truck->id)->first();
            $allocation->total_cost = $costs;
            $allocation->truck_id = $truck->id;
            $allocation->driver_id = $driver->driver_id;
            $allocation->trailer_id = $trailer1->trailer_id;
            $allocation->created_by = Auth::user()->id;

            // $truck=Truck::where('id',$request->truck_id)->first();
            $truck->status = 1;
            $truck->update();

            $allocation->save();
        }

        // For User Log
        // SystemLogHelper::logSystemActivity('Bulk Allocation Trucks Adding', auth()->user()->id, auth()->user()->fname . ' ' . auth()->user()->lname . ' has added bulk trucks to and Allocation');

        $msg = "Trucks were added successfully !";
        return back()->with('success', $msg);
    }


     // For Remove Bulk Truck Allocation
    public function remove_bulk_trucks(Request $request)
    {
        if ($request->input('selectedRows1') == null) {
            return back()->with('error', 'Please Select Truck To Remove');
        }
        $selectedRows = $request->input('selectedRows1');

        foreach ($selectedRows as $rowId) {

            $allocation = TruckAllocation::find($rowId);
            $id = Allocation::where('id', $allocation->allocation_id)->first();
            if ($id->payment_mode == 1) {

                $current_amount = $id->estimated_pay;
                $new_amount = $current_amount - $id->amount;
                $new_real_amount = $new_amount * $id->currency->rate;

                $id->estimated_pay = $new_amount;
                $id->real_amount = $new_real_amount;
                $id->usd_income = $new_amount;
                $id->rate = $id->currency->rate;

                $id->update();
            } else {
                $trailer = TrailerAssignment::where('truck_id', $allocation->truck_id)->get();
                $id = Allocation::where('id', $allocation->allocation_id)->first();
                $capacity = 0;
                foreach ($trailer as $item) {
                    $capacity += $item->trailer->capacity;
                }

                $current_amount = $id->estimated_pay;
                $new_amount = $current_amount - $capacity * $id->amount;
                $new_real_amount = $new_amount * $id->currency->rate;
                $id->estimated_pay = $new_amount;
                $id->usd_income = $new_amount;
                $id->real_amount = $new_real_amount;
                $id->rate = $id->currency->rate;

                $id->update();
            }
            $truck = Truck::where('id', $allocation->truck_id)->first();
            $truck->status = 0;
            $truck->update();

            $allocation->delete();
        }

        $error = "Truck Was Removed Successfully !";
        return back()->with('error', $error);
    }

}
