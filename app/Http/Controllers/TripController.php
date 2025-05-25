<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Route;
use App\Models\Approval;
use App\Models\Currency;
use App\Models\Position;
use App\Models\RouteCost;
use App\Models\Allocation;
use App\Models\CargoNature;
use App\Models\PaymentMode;
use Illuminate\Http\Request;
use App\Models\ApprovalLevel;
use App\Models\AllocationCost;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{

    // For All Trip Requests
    public function goingload_requests()
    {
        $uid = Auth::user()->position;

        $position = Position::where('id', $uid)->first();

        // For Trip Requests
        $process = Approval::where('process_name', 'Trip Approval')->first();
        $data['level'] = ApprovalLevel::where('role_id', $uid)->where('approval_id', $process->id)->first();

        // For Completion Requests
        $process1 = Approval::where('process_name', 'Trip Completion Approval')->first();
        $data['level1'] = ApprovalLevel::where('role_id', $uid)->where('approval_id', $process1->id)->first();

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
        $cost->type =$request->type;
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

        $cost->type =$request->type;

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




}
