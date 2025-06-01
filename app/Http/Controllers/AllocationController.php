<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\User;
use App\Models\Route;
use App\Models\Truck;
use App\Models\Approval;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Position;
use App\Models\RouteCost;
use App\Models\TruckCost;
use App\Models\Allocation;
use App\Models\CargoNature;
use App\Models\CurrencyLog;
use App\Models\PaymentMode;
use Illuminate\Http\Request;
use App\Models\ApprovalLevel;
use App\Models\AllocationCost;
use App\Models\CurrencyLogItem;
use App\Models\TruckAllocation;
use App\Helpers\SystemLogHelper;
use App\Models\AllocationRemark;
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
        // $allocations = Allocation::with(['customer', 'route', 'createdBy', 'nature', 'mode'])
        //     ->paginate(10);

        $uid = Auth::user()->position;
        $process = Approval::where('process_name', 'Allocation Approval')->first();
        $data['level'] = ApprovalLevel::where('role_id', $uid)->where('approval_id', $process->id)->first();
        $data['final'] = $process->levels;
        $data['tab'] = 'pending';
        $data['check'] = 'Approved By ' . Auth()->user()->fname . ' ' . Auth::user()->lname;
        $data['total_allocations'] = Allocation::count();
        $data['incomplete_allocations'] = Allocation::where('status', '<', 1)->whereNot('status', -2)->count();
        $data['pending_allocations'] = Allocation::where('status', '<', 2)->where('status', '>', 1)->whereNot('status', -2)->count();
        $data['approved_allocations'] = Allocation::where('status', '>', 2)->whereNot('status', -2)->count();
        $data['rejected_allocations'] = Allocation::where('status', -1)->whereNot('status', -2)->count();
        $data['pending'] = Allocation::latest()->where('status', '<', 3)->latest()->orderBy('id', 'desc')->whereNot('status', -2)->get();
        $data['active'] = Allocation::latest()->where('status', '>', 2)->latest()->orderBy('id', 'desc')->whereNot('status', -2)->limit(100)->get();
        $data['approved'] = Allocation::latest()->where('status', 3)->latest()->orderBy('id', 'desc')->whereNot('status', -2)->get();
        $data['incomplete'] = Allocation::where('status', '<', 1)->whereNot('status', -2)->get();

        return view('allocations.index', $data);
    }


    public function request_trip($id)
    {
        $id = base64_decode($id);
        $data['allocation'] = Allocation::find($id);
        $data['trucks'] = TruckAllocation::where('allocation_id', $id)->latest()->get();
        return view('trips.allocations.trip_detail', $data);
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
                'customer_id' => 'required',
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


            $customer = Customer::where('id', $request->customer_id)->first();
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
                $customer?->abbreviation ?? 'Trip',
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
        // $uid = Auth::user()->position;
        $uid = Auth::user()->roles->first()?->id;
        $positionId = Auth::user()->position_id;
        $position = Position::where('id', $positionId)->first();
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
        // dd($current);
        if ($current) {

            $data['current_person'] = $current->roles?->name ?? '--';
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


    // For Submitting Allocation Request
    public function submit_allocation($id)
    {

        $allocation = Allocation::where('id', $id)->first();
        $allocation->status = 1;
        $allocation->approval_status = 1;
        $allocation->state = 'Waiting Approval';


        $trucks_allocations = TruckAllocation::where('allocation_id', $allocation->id)->get();

        $total_pulling = 0;
        $total_semi = 0;


        foreach ($trucks_allocations as $truck) {

            // For Counting Pulling Trucks
            if ($truck->truck->truck_type == 2) {
                $total_pulling = $total_pulling + 1;
            }
            // For Counting Semi Trucks
            else {
                $total_semi = $total_semi + 1;
            }
        }



        // Start of Automatic Allocation Costs Deletion

        // For Pulling Trucks
        if ($total_pulling == 0) {
            $pulling_costs = AllocationCost::where('allocation_id', $allocation->id)->where('type', 'Pulling')->get();
            foreach ($pulling_costs as $cost) {
                $cost->delete();
            }
        }


        // For Semi Trucks
        if ($total_semi == 0) {
            $semi_costs = AllocationCost::where('allocation_id', $allocation->id)->where('type', 'Semi')->get();
            foreach ($semi_costs as $cost) {
                $cost->delete();
            }
        }

        //End of Automatic Allocation Costs Deletion

        $allocation->update();

        // For User Log
        SystemLogHelper::logSystemActivity('Allocation Submission', auth()->user()->id, auth()->user()->fname . ' ' . auth()->user()->lname . ' has Submitted an Allocation');

        // Start Of Approval Email Alert
        // $process = Approval::where('process_name', 'Allocation Approval')->first();
        // $level = ApprovalLevel::where('approval_id', $process->id)->first();
        // $employees = User::where('position_id', $level->role_id)->get(); //To be Modified

        // $email_data = array(
        //     'subject' => $allocation->ref_no . ' Allocation Request Approval',
        //     'view' => 'emails.allocations.fleet-approval',
        //     'allocation' => $allocation,
        // );

        // $job = (new \App\Jobs\SendEmail($email_data, $employees));
        // dispatch($job);
        // end of Approval Email Alert

        return response()->json([
            'status' => 200,
            'errors' => 'Updated',
            'route_truck' => route('allocations.list'),

        ]);
    }




    // For Approving allocation
    public function  approveAllocation(Request $request)
    {



        // $uid = Auth::user()->position;
        $role_id = Auth::user()->roles->first()?->id;
        $positionId = Auth::user()->position_id;
        $position = Position::where('id', $positionId)->first();
        $approval = Approval::where('process_name', 'Allocation Approval')->first();

        $level = ApprovalLevel::where('role_id', $role_id)->where('approval_id', $approval->id)->first();

        if ($level) {
            $allocation = Allocation::where('id', $request->allocation_id)->first();



            $approval_id = $level->approval_id;

            $approval = Approval::where('id', $approval_id)->first();

            if ($approval->levels == $level->level_name) {

                $approvalStatus = $allocation->approval_status;

                // Checking Trip
                $trip = Trip::where('allocation_id', $request->allocation_id)->first();

                if ($trip) {
                    // For Allocation Update
                    Allocation::where('id', $request->allocation_id)->update(
                        [
                            'approval_status' => $approvalStatus + 1,
                            'state' => Auth::user()->name,
                            'approver_id' => Auth::user()->id,
                            'status' => '4'
                        ]
                    );
                } else {
                    // For Allocation Update
                    Allocation::where('id', $request->allocation_id)->update(
                        [
                            'approval_status' => $approvalStatus + 1,
                            'state' => Auth::user()->name,
                            'status' => '3'
                        ]
                    );
                }



                // For Approval Remark
                AllocationRemark::create([
                    'allocation_id' => $request->allocation_id,
                    'remark' => $request->reason,
                    'remarked_by' => $level->label_name,
                    'created_by' => Auth::user()->id,
                ]);


                // Start of Final Approval Email Notification
                $creator = User::where('id', $allocation->created_by)->first();
                // $employees = User::where('position', $creator->position)->get();

                // $email_data = array(
                //     'subject' => $allocation->ref_no . ' Allocation Request Approval',
                //     'view' => 'emails.allocations.final-approval',
                //     'allocation' => $allocation,

                // );
                // $job = (new \App\Jobs\SendEmail($email_data, $employees));
                // dispatch($job);

                // end of Final Email Notification
                $msg = 'Allocation request has been approved Successfully !';
                return back()->with('msg', $msg);
            } else {
                // To be approved by another person
                $allocation = Allocation::where('id', $request->allocation_id)->first();
                $approvalStatus = $allocation->approval_status;

                if ($approvalStatus  == $level->level_name) {


                    // For Allocation Update
                    Allocation::where('id', $request->allocation_id)->update(
                        [
                            'approval_status' => $approvalStatus + 1,
                            'state' => Auth::user()->full_name,
                            'status' => 1,
                            'approver_id' => Auth::user()->id

                        ]
                    );

                    // Start Of Approval Remark
                    AllocationRemark::create(
                        [
                            'allocation_id' => $request->allocation_id,
                            'remark' => $request->reason,
                            'remarked_by' => $level->label_name,
                            'created_by' => Auth::user()->id,
                            'status' => 0
                        ]
                    );



                    // Start Of Approval Email Alert
                    $approved = $allocation->approval_status + 1;
                    $level = ApprovalLevel::where('level_name', $approved)->where('approval_id', $approval->id)->first();
                    $employees = User::where('position', $level->role_id)->get();


                    $email_data = array(
                        'subject' => $allocation->ref_no . ' Allocation Request Approval',
                        'view' => 'emails.allocations.fleet-approval',
                        'allocation' => $allocation,

                    );
                    // $job = (new \App\Jobs\SendEmail($email_data, $employees));
                    // dispatch($job);
                    // end of Approval Email Alert

                    $msg = 'Approved By ' . Auth::user()->positions->name;
                    return back()->with('msg', $msg);
                } else {
                    $msg = "Failed To Approve !";
                    return back()->with('msg', $msg);
                }
            }
        } else {
            $msg = "Failed To Approve !";
            return back()->with('msg', $msg);
        }
    }


    // For New  Disapproval Allocation By Level
    public function disapproveAllocation(Request $request)
    {

        $allocation = Allocation::where('id', $request->allocation_id)->first();

         $role_id = Auth::user()->roles->first()?->id;
        $positionId = Auth::user()->position_id;
        $position = Position::where('id', $positionId)->first();
        $approval = Approval::where('process_name', 'Allocation Approval')->first();

        $level = ApprovalLevel::where('role_id', $role_id)->where('approval_id', $approval->id)->first();

        if ($level) {
            // if ($allocation->disapprover_id == Auth::user()->id) {
            //     return back()->with('error', 'Sorry,You have already Disapproved this allocation');
            // }

            $approval_id = $level->approval_id;
            $approval = Approval::where('id', $approval_id)->first();

            if ($level->level_name == 1) {

                $approvalStatus = $allocation->approval_status;
                $allocation->state = Auth::user()->fname . ' ' . Auth::user()->lname;
                $allocation->disapprover_id = Auth::user()->id;
                $allocation->status = -1;
                $allocation->approval_status = $approvalStatus - 1;



                $remark = new AllocationRemark();
                $remark->allocation_id = $request->allocation_id;
                $remark->remark = $request->reason;
                $remark->remarked_by =  $level->label_name;

                $remark->status = $approvalStatus - 1;
                $remark->created_by = Auth::user()->id;
                $remark->save();

                $allocation->update();



                // Start Of Approval Email Alert
                // $employees = User::where('id', $allocation->created_by)->get();
                // $email_data = array(
                //     'subject' => $allocation->ref_no . ' Allocation Request Disapproval',
                //     'view' => 'emails.allocations.fleet-disapproval',
                //     'allocation' => $allocation,

                // );
                // $job = (new \App\Jobs\SendEmail($email_data, $employees));
                // dispatch($job);
                // end of Approval Email Alert


                return back()->with('Allocation has been Disapproved Successfully !');
            } else {
                $approvalStatus = $allocation->approval_status;
                $allocation->state = Auth::user()->fname . ' ' . Auth::user()->lname;
                $allocation->disapprover_id = Auth::user()->id;
                $allocation->approver_id = Auth::user()->id;

                $allocation->approval_status = $approvalStatus - 1;


                $remark = new AllocationRemark();
                $remark->allocation_id = $request->allocation_id;
                $remark->remark = $request->reason;
                $remark->remarked_by = $level->label_name;
                $remark->status = $approvalStatus - 1;
                $remark->created_by = Auth::user()->id;
                $remark->save();

                $allocation->update();

                // Start Of Approval Email Alert
                $approved = $allocation->approval_status;
                $level = ApprovalLevel::where('level_name', $approved)->first();
                $employees = User::where('position', $level->role_id)->get();


                // $email_data = array(
                //     'subject' => $allocation->ref_no . ' Allocation Request Disapproval',
                //     'view' => 'emails.allocations.fleet-disapproval',
                //     'allocation' => $allocation,

                // );
                // $job = (new \App\Jobs\SendEmail($email_data, $employees));
                // dispatch($job);
                // end of Approval Email Alert

                return back()->with('msg', 'Allocation has been Disapproved Successfully !');
            }
        } else {
            $msg = "Failed To Disapprove !";
            return back()->with('msg', $msg);
        }
    }

    // For Delete Truck Allocation Request
    public function delete_allocation($id)
    {
        $allocation = Allocation::find($id);

        $truck = TruckAllocation::where('allocation_id', $allocation->id)->get();
        // dd($truck);
        if ($truck) {
            foreach ($truck as $item) {

                $trck = Truck::where('id', $item->truck_id)->first();
                //  dd($trck);
                $trck->status = 0;
                $trck->update();

                $item->delete();
            }
        }

        $allocation->delete();
        $msg = "Allocation Was Deleted Successfully !";
        return redirect()->route('allocations.list')->with('msg', $msg);
        // return redirect('/trips/allocation-requests')->with('msg',$msg);

    }


    //  For Revoke Allocation Request
    public function revoke_allocation($id)
    {
        $allocation = Allocation::find($id);
        $allocation->status = 0;
        $allocation->state = 'Revoked';
        $allocation->update();

        $msg = "Allocation Was Revoked Successfully !";
        return redirect()->route('allocations.list')->with('msg', $msg);
    }



     // For Truck cost Details
    public function viewTruckCostDetails($id)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Decode the ID from base64
            $id = base64_decode($id);

            // Find the truck allocation record
            $allocation = TruckAllocation::find($id);

            if (!$allocation) {
                throw new \Exception("Truck Allocation not found for ID: $id");
            }

            // Fetch the associated data
            $data['common'] = RouteCost::where('route_id', $allocation->allocation->route_id)->latest()->get();
            // $data['accounts'] = AccountingCodes::get();
            $data['currencies'] = Currency::latest()->get();
            $data['truck'] = Truck::find($allocation->truck_id);
            $data['trips'] = TruckAllocation::where('id', $id)->latest()->get();
            $data['allocation'] = Allocation::find($allocation->allocation_id);

            // Commit the transaction
            DB::commit();

            // Return the view with the data
            return view('trips.allocations.truck_cost', $data);
        } catch (\Exception $e) {
            // Rollback transaction if something goes wrong
            DB::rollBack();

            // Log the error for debugging
            Log::error('Error fetching truck cost details: ' . $e->getMessage());

            // Return error message to the user
            return response()->json([
                'status' => 500,
                'error' => 'An error occurred while fetching truck cost details. Please try again.',
            ]);
        }
    }



    // For Save Allocation Truck Cost
    public function addAllocationTruckCost(Request $request)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Validate the incoming request
            $request->validate(
                [
                    'currency_id' => 'required',
                    'amount' => 'required',
                    'cost_id' => 'required',
                ]
            );

            // Find the related RouteCost record
            $common = RouteCost::find($request->cost_id);
            if (!$common) {
                throw new \Exception("RouteCost not found for ID: {$request->cost_id}");
            }

            // Find the TruckAllocation record
            $allocation = TruckAllocation::where('truck_id', $request->truck_id)
                ->where('allocation_id', $request->allocation_id)
                ->with('truck')
                ->first();
            if (!$allocation) {
                throw new \Exception("TruckAllocation not found for Truck ID: {$request->truck_id} and Allocation ID: {$request->allocation_id}");
            }

            // Create a new TruckCost record
            $cost = new TruckCost();
            $cost->allocation_id = $allocation->id;
            $cost->truck_id = $request->truck_id;
            $cost->name = $common->name;
            $cost->type = $common->type;
            $cost->return = $common->return;
            $cost->route_id = $allocation->allocation->route_id;
            $cost->account_code = $common->account?->code??0000;
            $cost->advancable = $common->advancable;
            $cost->amount = $request->amount;

            // Find the currency record
            $currency = Currency::find($request->currency_id);
            if (!$currency) {
                throw new \Exception("Currency not found for ID: {$request->currency_id}");
            }

            // Calculate real_amount based on quantity
            if ($request->quantity > 0 || $common->quantity != null) {
                $cost->quantity = $request->quantity;
                $cost->real_amount = ($currency->rate * $request->amount) * $request->quantity;
            } else {
                $cost->real_amount = $currency->rate * $request->amount;
            }

            // Assign the remaining cost attributes
            $cost->currency_id = $request->currency_id;
            $cost->created_by = Auth::user()->id;
            $cost->rate = $currency->rate;

            // Save the new TruckCost record
            $cost->save();

            // Commit the transaction
            DB::commit();

            // Return a success message
            return back()->with('success', 'Truck Cost Added Successfully!');
        } catch (\Exception $e) {
            // dd($e);
            // Rollback transaction if something goes wrong
            DB::rollBack();

            // Log the error for debugging
            Log::error('Error adding truck cost: ' . $e->getMessage());

            // Return error message to the user
            return response()->json([
                'status' => 500,
                'error' => 'An error occurred while adding the truck cost. Please try again.',
            ]);
        }
    }


}
