<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Route;
use App\Models\Approval;
use App\Models\Currency;
use App\Models\Position;
use App\Models\TripCost;
use App\Models\RouteCost;
use App\Models\Allocation;
use App\Models\CargoNature;
use App\Models\PaymentMode;
use Illuminate\Http\Request;
use App\Models\ApprovalLevel;
use App\Models\AllocationCost;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{

    // For All Trip Requests
    public function goingload_requests()
    {
        $uid = Auth::user()->roles->first()?->id;
        $positionId = Auth::user()->position_id;
        $position = Position::where('id', $positionId)->first();
        // For Trip Requests

        $process = Approval::firstOrCreate(
            ['process_name' => 'Trip Approval'],
            [
                'levels' => 0,
                'escallation' => false, // or true, depending on your logic
                'escallation_time' => null
            ]
        );

        $data['level'] = ApprovalLevel::where('role_id', $uid)->where('approval_id', $process->id)->first() ?? null;

        // For Completion Requests

        $process1 = Approval::firstOrCreate(
            ['process_name' => 'Trip Completion Approval'],
            [
                'levels' => 0,
                'escallation' => false, // or true, depending on your logic
                'escallation_time' => null
            ]
        );
        $data['level1'] = ApprovalLevel::where('role_id', $uid)->where('approval_id', $process1->id)->first() ?? null;

        $data['pending'] = Trip::where('type', 1)->where('status', 1)->orWhere('status', '-1')->latest()->get();
        $data['completed'] = Trip::where('status', 0)->where('type', 1)->where('state', 4)->latest()->get();

        $data['active'] = Trip::where('status', 0)->where('type', 1)->where('state', '!=', 5)->where('state', '!=', 4)->latest()->get();
        $data['completion'] = Trip::where('status', 0)->where('type', 1)->where('state', 5)->latest()->get();

        $data['check'] = 'Approved By ' . $position;
        $data['completed_trips'] = Trip::where('type', 1)->where('status', 0)->latest()->count();
        $data['total_trips'] = Trip::where('type', 1)->count();
        $data['active_trips'] = Trip::where('type', 1)->where('status', '>', 1)->where('state', '!=', 5)->latest()->count();
        $data['trip_requests'] = Trip::where('type', 1)->where('status', 1)->orWhere('status', '-1')->latest()->count();

        return view('trips.goingload.requests', $data);
    }

    // For GoingLoad Trip Details
    public function goingload_trip($id)
    {
        $id = base64_decode($id);
        $uid = Auth::user()->position;
        $allocation = Allocation::find($id);;

        $position = Position::where('id', $uid)->first();
        $process = Approval::where('process_name', 'Trip Approval')->first();
        $trip = Trip::where('allocation_id', $allocation->id)->first();
        $latest_status = $trip->approval_status;
        $current = ApprovalLevel::where('level_name', $latest_status)->where('approval_id', $process->id)->first();
        if ($current) {

            $data['current_person'] = $current->roles->name;
        } else {
            if ($latest_status <= 0) {
                $data['current_person'] = 'Assistant Fleet Controller';
            } else {
                $data['current_person'] = 'Completed';
            }
        }
        $process1 = Approval::where('process_name', 'Truck Initiation Approval')->first();
        $data['level'] = ApprovalLevel::where('role_id', $uid)->where('approval_id', $process->id)->first();
        $data['level1'] = ApprovalLevel::where('role_id', $uid)->where('approval_id', $process1->id)->first();
        $data['check'] = 'Approved By ' . $position;
        $data['allocation'] = Allocation::find($id);
        $data['trip'] = Trip::where('allocation_id', $allocation->id)->first();
        $data['routes'] = Route::latest()->where('status', 1)->get();
        $data['nature'] = CargoNature::latest()->where('status', 0)->get();
        $data['mode'] = PaymentMode::latest()->where('status', 0)->get();
        $data['currency'] = Currency::latest()->where('status', 1)->get();
        return view('trips.goingload.detail', $data);
    }


    //For Backload Trips
    public function backload_requests()
    {
        $uid = Auth::user()->roles->first()?->id;
        $positionId = Auth::user()->position_id;
        $position = Position::where('id', $positionId)->first();
        // For Trip Requests

        $process = Approval::firstOrCreate(
            ['process_name' => 'Trip Approval'],
            [
                'levels' => 0,
                'escallation' => false, // or true, depending on your logic
                'escallation_time' => null
            ]
        );

        $data['level'] = ApprovalLevel::where('role_id', $uid)->where('approval_id', $process->id)->first() ?? null;

        // For Completion Requests

        $process1 = Approval::firstOrCreate(
            ['process_name' => 'Trip Completion Approval'],
            [
                'levels' => 0,
                'escallation' => false, // or true, depending on your logic
                'escallation_time' => null
            ]
        );
        $data['level1'] = ApprovalLevel::where('role_id', $uid)->where('approval_id', $process1->id)->first() ?? null;

        $data['pending'] = Trip::where('type', 2)->where('status', 1)->orWhere('status', '-1')->latest()->get();
        $data['completed'] = Trip::where('status', 0)->where('type', 2)->where('state', 4)->latest()->get();

        $data['active'] = Trip::where('status', 0)->where('type', 2)->where('state', '!=', 5)->where('state', '!=', 4)->latest()->get();
        $data['completion'] = Trip::where('status', 0)->where('type', 2)->where('state', 5)->latest()->get();



        $data['check'] = 'Approved By ' . Auth()->user()->position?->name;
        $data['completed_trips'] = Trip::where('type', 2)->where('status', 0)->latest()->count();
        $data['total_trips'] = Trip::where('type', 2)->count();
        $data['active_trips'] = Trip::where('type', 2)->where('status', '>', 1)->latest()->count();
        $data['trip_requests'] = Trip::where('type', 2)->where('status', 1)->orWhere('status', '-1')->latest()->count();



        return view('trips.backloads.requests', $data);
    }

    // For Backload Trip Details
    public function backload_trip($id)
    {
        // Start transaction
        DB::beginTransaction();

        try {
            // Decode the base64 ID and retrieve the user's position
            $id = base64_decode($id);
            $uid = Auth::user()->position;

            // Get approval process for Trip Approval
            // $process = Approval::where('process_name', 'Trip Approval')->first();

            $process = Approval::firstOrCreate(
                ['process_name' => 'Trip Approval'],
                [
                    'levels' => 0,
                    'escallation' => false, // or true, depending on your logic
                    'escallation_time' => null
                ]
            );

            // Retrieve allocation and trip details
            $allocation = Allocation::findOrFail($id); // Using findOrFail to throw an exception if not found
            $trip = Trip::where('allocation_id', $allocation->id)->first();

            // Get the latest approval status and corresponding role
            $latest_status = $trip->approval_status;
            $current = ApprovalLevel::where('level_name', $latest_status)
                ->where('approval_id', $process->id)
                ->first();

            // Determine current person based on approval status
            if ($current) {
                $data['current_person'] = $current->roles?->name??'--';
            } else {
                $data['current_person'] = ($latest_status <= 0) ? 'Assistant Fleet Controller' : 'Completed';
            }

            // Retrieve the approval level for the current user
            $data['level'] = ApprovalLevel::where('role_id', $uid)
                ->where('approval_id', $process->id)
                ->first();

            // Other necessary data to be passed to the view
            $data['check'] = 'Approved By ' . Auth()->user()->positions?->name??'--';
            $data['allocation'] = $allocation;
            $data['trip'] = $trip;
            $data['routes'] = Route::where('status', 1)->latest()->get();
            $data['nature'] = CargoNature::where('status', 0)->latest()->get();
            $data['mode'] = PaymentMode::where('status', 0)->latest()->get();
            $data['currency'] = Currency::where('status', 1)->latest()->get();

            // Commit the transaction
            DB::commit();

            // Return the view with the data
            return view('trips.backloads.detail', $data);
        } catch (\Exception $e) {

            dd($e);
            // Rollback the transaction in case of error
            DB::rollBack();

            // Log the error
            Log::error('Error in backloadTripDetail: ' . $e->getMessage());

            // Return an error message to the user
            return back()->withErrors('An error occurred while fetching trip details.');
        }
    }

    // For Add Allocation cost
    public function add_cost(Request $request)
    {
        $cost = new AllocationCost();
        $cost->allocation_id = $request->allocation_id;
        $route = RouteCost::where('id', $request->cost_id)->first();
        // dd($request->allocation_id);
        $cost->name = $route->name;
        $cost->amount = $request->amount;
        $cost->currency_id = $request->currency_id;
        $cost->editable = $request->editable == true ? '1' : '0';
        $cost->return = $request->return == true ? '1' : '0';
        $currency = Currency::find($request->currency_id);

        if ($request->quantity > 0) {
            $cost->amount = $request->amount;
            $cost->real_amount = $request->amount * $request->quantity;
            $cost->quantity = $request->quantity;
        } else {
            $cost->amount = $request->amount;
            $cost->real_amount = $request->amount * $currency->rate;
        }
        // $cost->real_amount = $currency->rate * $request->amount;
        $cost->rate = $currency->rate;
        $cost->account_code = $route->account_code;
        $cost->route_id = $route->route_id;
        $cost->type = $request->type;
        $cost->status = 0;
        $cost->created_by = Auth::user()->id;
        $cost->save();

        return back()->with('msg', 'Allocation cost has been updated successfully !');
    }

    // For Update Allocation cost
    public function update_cost(Request $request)
    {

        // dd($request->id);
        $cost = AllocationCost::find($request->id);
        $cost->name = $request->name;
        $currency = Currency::find($request->currency_id);

        if ($request->quantity > 0) {
            $cost->amount = $request->amount;
            $cost->real_amount = $request->amount * $request->quantity;
            $cost->quantity = $request->quantity;
        } else {
            $cost->amount = $request->amount;
            $cost->real_amount = $request->amount * $currency->rate;
        }
        // $cost->amount = $request->amount;
        $cost->currency_id = $request->currency_id;
        // $cost->real_amount = $currency->rate * $request->amount;
        $cost->rate = $currency->rate;
        $cost->return = $request->return == true ? '1' : '0';
        $cost->editable = $request->editable == true ? '1' : '0';

        $cost->type = $request->type;

        $cost->update();

        return back()->with('msg', 'Allocation cost has been updated successfully !');
    }

    // For Delete Allocation cost
    public function delete_cost($id)
    {
        $cost = AllocationCost::find($id);
        $cost->delete();
        $msg = "Allocation Cost Was Removed Successfully !";
        return back()->with('msg', $msg);
    }


    // For Submit Trip
    public function submit_trip($id)
    {


        $id = base64_decode($id);
        $allocation = Allocation::find($id);

        $trip = Trip::where('allocation_id', $allocation->id)->first();

        if ($trip == null) {

            $trip = new Trip();
            $trip->ref_no = $allocation->ref_no; //reference number
            $trip->allocation_id = $id;
            $trip->total_payment = $allocation->amount;
            $trip->initiated_date = date('Y-m-d');
            $trip->type = $allocation->type;

            $trip->status = 0;
            $trip->state = 2;
            $trip->created_by = Auth::user()->id;
            $trip->approval_status = 1;
            $trip->save();
            // $ref='GL-'.$allocation->customer->abbreviation. date('y', strtotime(date('Y-m-d'))).date('m', strtotime(date('Y-m-d'))).'-'.$allocation->id;
            $trip = Trip::where('ref_no', $allocation->ref_no)->first();
            $costs = AllocationCost::where('allocation_id', $allocation->id)->get();
            foreach ($costs as $item) {
                $tripcost = new TripCost();
                $tripcost->trip_id = $trip->id;
                $tripcost->cost_id = $item->id;
                $tripcost->name = $item->name;
                $tripcost->amount = $item->real_amount;

                $tripcost->status = 1;
                $tripcost->created_by = Auth::user()->id;
                $tripcost->save();
            }
        } else {
            $trip->approval_status = 1;
            $trip->update();
        }
        $allocation->status = 4;
        $allocation->update();



        if ($allocation->type == 2) {
            return redirect('trips/backload-requests')->with('msg', 'Trip Request has been Submitted Successfully !');
        } else {
            return redirect('trips/trip-requests')->with('msg', 'Trip Request has been Submitted Successfully !');
        }
    }



    // For all Finance trips Trips
    public function finance_trips()
    {
        $uid = Auth::user()->position;
        $process = Approval::firstOrCreate(
            ['process_name' => 'Trip Approval'],
            [
                'levels' => 0,
                'escallation' => false, // or true, depending on your logic
                'escallation_time' => null
            ]
        );
        $data['tab'] = 'pending';
        $data['level'] = ApprovalLevel::where('role_id', $uid)->where('approval_id', $process->id)->first();
        $data['pending'] = Trip::latest()->whereNot('status', 0)->get();
        $data['approved'] = Trip::latest()->where('state', 2)->where('status', 0)->get();
        $data['completed'] = Trip::latest()->where('state', 4)->get();

        $data['pending_trips'] = Trip::where('state', 0)->latest()->count();
        $data['unpaid_trips'] = Trip::where('state', 2)->latest()->count();
        $data['rejected_trips'] = Trip::where('state', -1)->latest()->count();
        $data['paid_trips'] = Trip::where('state', 4)->latest()->count();

        $position = Position::where('id', $uid)->first();
        $data['check'] = 'Approved By ' . $position;
        return view('trips.finance_trips', $data);
    }
}
