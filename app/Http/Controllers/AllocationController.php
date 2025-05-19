<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Route;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\RouteCost;
use App\Models\Allocation;
use App\Models\CargoNature;
use App\Models\CurrencyLog;
use App\Models\PaymentMode;
use Illuminate\Http\Request;
use App\Models\AllocationCost;
use App\Models\CurrencyLogItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AllocationController extends Controller
{
    public function index()
    {
        $allocations = Allocation::with(['customer', 'route', 'createdBy', 'cargoNature', 'paymentMode'])
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
        // Begin database transaction
        DB::beginTransaction();

        try {
            // Validate the input data with custom error messages
            $validate = Validator::make($request->all(), [
                'customer_id' => 'required',
                'cargo' => 'required',
                'cargo_ref' => 'required',
                'cargo_nature' => 'required',
                'amount' => 'required|numeric',
                'payment_mode' => 'required',
                'payment_curency' => 'required',
                'container' => 'required',
            ], [
                'customer_id.required' => 'Please select a customer.',
                'cargo.required' => 'Cargo description is required.',
                'cargo_ref.required' => 'Cargo reference is required.',
                'cargo_nature.required' => 'Cargo nature is required.',
                'amount.required' => 'Amount is required.',
                'amount.numeric' => 'Amount must be a number.',
                'payment_mode.required' => 'Payment mode is required.',
                'payment_curency.required' => 'Payment currency is required.',
                'container.required' => 'Container information is required.',
            ]);

            // Handle validation failure
            if ($validate->fails()) {
                return redirect()->back()
                    ->withErrors($validate)
                    ->withInput();
            }

            // Check for currency log
            $currencyLog = CurrencyLog::latest()->first();
            if ($currencyLog == null) {
                return redirect()->back()
                    ->with('error', 'Sorry, there is no currency log!')
                    ->withInput();
            }

            // Get currency rate
            $currency = CurrencyLogItem::where('currency_log_id', $currencyLog->id)
                ->where('currency_id', $request->payment_curency)
                ->first();

            // Create new allocation
            $allocation = new Allocation();
            $allocation->Customer_id = $request->customer_id;
            $allocation->cargo = $request->cargo;
            $allocation->cargo_ref = $request->cargo_ref;
            $allocation->cargo_nature_id = $request->cargo_nature;
            $allocation->amount = $request->amount;
            $allocation->quantity = $request->quantity;
            $allocation->payment_mode = $request->payment_mode;
            $allocation->payment_currency = $request->payment_curency;
            $allocation->start_date = $request->start_date;
            $allocation->end_date = $request->end_date;
            $allocation->route_id = $request->route_id;
            $allocation->loading_site = $request->loading_point;
            $allocation->offloading_site = $request->offloading_point;
            $allocation->unit = $request->unit;
            $allocation->type = $request->type;
            $allocation->container = $request->container;
            $allocation->container_type = $request->container_type;
            $allocation->rate = $currency->rate;
            $allocation->clearance = $request->clearance;
            $allocation->dimensions = $request->dimensions;
            $allocation->created_by = Auth::user()->id;

            $allocation->save();

            // Generate reference number based on allocation type
            $allocation = Allocation::find($allocation->id);
            $type = $allocation->type;
            if ($type == 1) {
                $allocation->ref_no = 'GL-' . $allocation->customer->abbreviation . date('y') . date('m') . '-' . $allocation->id;
            } else {
                $allocation->ref_no = 'BL-' . $allocation->customer->abbreviation . date('y') . date('m') . '-' . $allocation->id;
            }
            $allocation->update();

            $id = $allocation->id;

            // Insert related allocation costs
            $route = RouteCost::where('route_id', $request->route_id)->where('status', 0)->get();
            foreach ($route as $item) {
                $cost = new AllocationCost();
                $cost->allocation_id = $id;
                $cost->name = $item->name;
                $cost->vat = $item->vat;
                $cost->return = $item->return;
                $cost->editable = $item->editable;
                $cost->type = $item->type;
                $cost_currency = 1; // Placeholder for currency rate (review if dynamic rate needed)
                $real_amount = $item->amount * $cost_currency;
                if ($item->quantity > 0) {
                    $cost->amount = $item->amount;
                    $cost->real_amount = $real_amount * $item->quantity;
                    $cost->quantity = $item->quantity;
                } else {
                    $cost->amount = $item->amount;
                    $cost->real_amount = $real_amount;
                }
                $cost->currency_id = $item->currency_id;
                $cost->account_code = $item->account_code;
                $cost->rate = $cost_currency;
                $cost->route_id = $item->route_id;
                $cost->status = 0;
                $cost->created_by = Auth::user()->id;
                $cost->save();
            }

            // Log the activity (uncommented from original)
            // SystemLogHelper::logSystemActivity(
            //     'Allocation Creation',
            //     auth()->user()->id,
            //     auth()->user()->fname . ' ' . auth()->user()->lname . ' has created an Allocation'
            // );

            // Commit the transaction
            DB::commit();

            // Redirect to truck allocation route with success message
            return redirect()->route('flex.truck-allocation', base64_encode($id))
                ->with('success', 'Allocation created successfully');
        } catch (\Exception $e) {


            // Rollback transaction in case of any exception
            DB::rollBack();

            // Log the error for debugging
            Log::error('Error saving new allocation detail: ' . $e->getMessage());

            // Redirect back with error message
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
            'cargo_nature_id' => 'required|exists:cargo_natures,id',
            'payment_mode_id' => 'required|exists:payment_modes,id',
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
                'cargo_nature_id',
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
}
