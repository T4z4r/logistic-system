<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Truck;
use App\Models\Allocation;
use Illuminate\Http\Request;
use App\Models\TruckAllocation;

class LoadingController extends Controller
{
      // For Loading Trucks
    public function index(){

        $data['unloaded_trucks']=TruckAllocation::where('loaded',0)->latest()->where('rescue_status',0)->get();
        $data['loaded_trucks']=TruckAllocation::where('loaded','>',0)->where('offloaded',0)->latest()->where('rescue_status',0)->get();
        $data['offloaded_trucks']=TruckAllocation::where('loaded','>',0)->where('offloaded','>',0)->where('rescue_status',0)->latest()->get();

        return view('loading_trucks.index',$data);
    }



        // For Load Truck
        public function load_truck(Request $request)
        {

            // request()->validate(
            //     [
            //         'loading_date' => 'required',
            //         'truck_id' => 'required',
            //         'quantity' => 'required',
            //     ]
            // );
            $allocation = TruckAllocation::where('id', $request->id)->first();

            $capacity = $allocation->trailer->capacity + 1;
            $loaded = $request->quantity;


            if ($capacity >= $loaded) {
                if ($allocation->allocation->payment_mode == 1)
                {
                // For Per Truck Mode
                $usd_income=$allocation->allocation->amount;
                $income=($allocation->allocation->amount*$allocation->allocation->rate);


                } else {
                    // For Per Tone Mode
                    $usd_income=$request->quantity*$allocation->allocation->amount;
                    $income=($loaded*$allocation->allocation->amount *$allocation->allocation->rate);
                }
                // For Loading at the first time
                if ($allocation->loaded == 0) {
                    $allocation::where('id', $request->id)->update(
                        [
                            'loaded' => $loaded,
                            'status' => 2,
                            'loading_date' => Carbon::parse($request->loading_date)->format('Y-m-d'),
                            'usd_income'=>$usd_income ,
                            'income'=>$income,
                        ]
                    );
                }
                // For Editing Loadng
                else {
                    $allocation::where('id', $request->id)->update(
                        [
                            'loaded' => $loaded,
                            'loading_date' => Carbon::parse($request->loading_date)->format('Y-m-d'),
                            'usd_income'=>$usd_income ,
                            'income'=>$income,
                        ]
                    );
                }
            } else {
                $error = 'Sorry, the Loaded amount is greater than the truck_capacity';

                return redirect()->back()->with('error', $error);
            }


        }



        // For Offload Truck
    public function offload_truck(Request $request)
    {

    

        $allocation = TruckAllocation::where('id', $request->id)->first();
        $capacity = $allocation->trailer->capacity + 1;
        $loaded = $allocation->loaded;
        if ($request->quantity > $loaded) {
            $error = 'Sorry, the offloaded amount is invalid';
            return back()->with('error', $error);
        }


        $id = Allocation::where('id', $allocation->allocation_id)->first();
        //For Per Truck Payment
        if ($id->payment_mode == 1) {
            $allocation->income = $id->amount * $id->currency->rate;
            $allocation->usd_income = $id->amount ;

        } else {

            $capacity = $request->quantity;

            $allocation->income = $capacity * $id->amount * $id->currency->rate;
            $allocation->usd_income = $capacity * $id->amount ;

        }
        $allocation->offloaded = $request->quantity;
        $allocation->offload_date = Carbon::parse($request->offloading_date)->format('Y-m-d');
        $allocation->status = 3;
        if ($request->hasfile('pod')) {

            $newImageName = $request->pod->hashName();
            $request->pod->move(public_path('storage/pod'), $newImageName);

            $allocation->pod = $newImageName;
        }

        $allocation->update();

        $truck = Truck::where('id', $allocation->truck_id)->first();
        $truck->location = $allocation->allocation->route->destination;
        if ($id->type == 1) {
            $truck->status = 0;
        } else {
            $truck->status = 2;
        }
        $truck->update();

        

        $msg = 'Truck Load Has been Offloaded Successfully';

        return  redirect()->back()->with('msg', $msg);
    }
}
