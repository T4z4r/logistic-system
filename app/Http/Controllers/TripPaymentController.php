<?php

namespace App\Http\Controllers\Accounts;

use DateTime;
use Carbon\Carbon;
use App\Models\Trip;
use App\Models\User;
// use NumberFormatter;
use NumberFormatter;
use App\Models\Route;
use App\Models\Truck;
use Pdf as FacadePdf;
use App\Models\Ledger;
use App\Models\Company;
use App\Models\Approval;
use App\Models\Customer;
use App\Models\Position;
use App\Models\Allocation;
use App\Models\Department;
use App\Models\TripRemark;
use App\Models\CurrencyLog;
use App\Models\TripInvoice;
use Illuminate\Http\Request;
use App\Models\ApprovalLevel;
use App\Models\InvoicedTruck;
use App\Models\PaymentMethod;
use App\Models\CustomerInvoice;
use App\Models\TruckAllocation;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\SystemLogHelper;
use App\Models\Settings\Currency;
use App\Models\TripInvoiceRemark;
// use Barryvdh\DomPDF\Facade\Pdf as FacadePdfStore;
use Faker\Provider\ar_EG\Payment;
use App\Models\TripInvoicePayment;
use Illuminate\Support\Facades\DB;
use App\Models\Account\Transaction;
use App\Models\InvoicedTruckIncome;
// use PhpOffice\PhpWord\Shared\Converter;
use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use App\Models\Account\LedgerAccount;
use App\Models\Account\ProcessLedger;
use Illuminate\Support\Facades\Validator;
use App\Models\Account\ProcessLedgerMapper;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Current;

class TripPaymentController extends Controller
{

    // For Transaction Service Provider
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    //For All Trips

    public function all_trips()
    {
        $uid = Auth::user()->position_id;
        $process = Approval::where('process_name', 'Invoice Approval')->first();
        $data['level'] = ApprovalLevel::where('role_id', $uid)->where('approval_id', $process->id)->first();
        $data['pending'] = Trip::where('state', '>=', 2)->latest()->orderBy('id', 'desc')->get();
        $data['completed'] = Trip::where('state', 5)->latest()->orderBy('id', 'desc')->get();
        $data['check'] = 'Approved By ' . Auth()->user()->positions->name;
        $data['completed_trips'] = Trip::where('status', 0)->latest()->count();
        $data['total_trips'] = Trip::count();
        $data['active_trips'] = Trip::where('status', 1)->latest()->count();
        $data['trip_requests'] = Trip::where('status', 1)->orWhere('status', '-1')->latest()->count();
        $data['tab'] = 'univoiced';

        // Start of Invoice statistics
        $data['total_draft_invoices'] = TripInvoice::where('status', '<=', 0)
            ->count();
        $data['total_pending_invoices'] = TripInvoice::where('status',  1)
            ->count();

        $data['total_approved_invoices'] = TripInvoice::where('status',  2)
            ->count();
        $data['total_invoices'] = TripInvoice::count();

        // end  of Invoice statistics

        $data['draft_invoices'] = TripInvoice::where('status', '<=', 0)
            ->where('type', 1)
            ->get();
        $data['other_draft_invoices'] = TripInvoice::where('status', '=<', 0)
            ->where('type', 2)
            ->count();

        return view('finance.trips.index', $data);
    }

    // For Invoice Tab Index
    // For Tab control

    public function tab_index($tab)
    {

        $tab  = $tab;
        // End
        $uid = Auth::user()->position_id;
        $process = Approval::where('process_name', 'Invoice Approval')->first();
        $data['level'] = ApprovalLevel::where('role_id', $uid)->where('approval_id', $process->id)->first();
        $data['pending'] = Trip::where('state', '>=', 2)->latest()->orderBy('id', 'desc')->get();
        $data['completed'] = Trip::where('state', 5)->latest()->orderBy('id', 'desc')->get();
        $data['check'] = 'Approved By ' . Auth()->user()->positions->name;

        $data['pending_trips'] = Trip::where('state', 0)->where('approval_status', 3)->latest()->count();
        $data['invoiced_trips'] = Trip::where('state', 2)->where('approval_status', 4)->latest()->count();
        $data['approved_trips'] = Trip::where('state', -1)->latest()->count();
        $data['completed_trips'] = Trip::where('state', 4)->latest()->count();
        // if ( !empty( $tab ) ) {
        //     $today = Carbon::today()->format( 'Y-m-d' );
        //     $initiate_array_status = [ 8, 9 ];
        // For Initiation
        //     $md_approval_array_status = [ 10 ];
        // For Md Approbing Initiated
        //     $payment_array_status = [ 11 ];
        // For Making Payment

        //     if ( $tab === 'active' ) {
        //         // $data[ 'trips' ] = Allocation::where( 'customer_id', $customer_id )->where( 'status', '5' )->latest()->get();
        //     } elseif ( $tab === 'completed' ) {
        //         // $data[ 'trips' ] = Allocation::where( 'customer_id', $customer_id )->where( 'status', '5' )->latest()->get();

        //     } elseif ( $tab === 'invoices' ) {
        //         // $data[ 'trips' ] = Allocation::where( 'customer_id', $customer_id )->where( 'status', '5' )->latest()->get();

        //     } elseif ( $tab === 'payments' ) {
        //         // $data[ 'invoices' ] = Allocation::where( 'customer_id', $customer_id )->where( 'status', '5' )->latest()->get();

        //         // $data[ 'purchases' ] = Purchase::where( 'supplier_id', $supplier_id )
        //         // ->where( 'status', 2 )->latest()->get();
        //     } elseif ( $tab === 'statement' ) {
        //         // $data[ 'purchases' ] = Purchase::where( 'supplier_id', $supplier_id )
        //         // ->where( 'status', 4 )->latest()->get();
        //     } else {
        //         return back();
        //     }
        // }
        $data['count'] = 1;
        $data['tab'] = $tab;
        return view('finance.trips.index', $data);
    }

    public function trip_detail($id)
    {
        $uid = Auth::user()->position_id;
        $process = Approval::where('process_name', 'Invoice Approval')->first();
        $level = ApprovalLevel::where('role_id', $uid)->where('approval_id', $process->id)->first();
        $data['level'] = ApprovalLevel::where('role_id', $uid)->where('approval_id', $process->id)->first();
        $data['final'] = $process->levels;
        $before = ApprovalLevel::where('level_name', (($process->levels) - 1))->first();
        // dd( $before );
        // $data[ 'before' ] = 'Approved By ' . $before->roles->name;
        $data['check'] = 'Approved By ' . Auth()->user()->positions->name;
        $data['allocation'] = Allocation::find($id);
        // $data[ 'check' ] = 'Approved By ' . Auth()->user()->position->name;
        $data['trucks'] = TruckAllocation::where('allocation_id', $id)->latest()->get();

        return view('finance.trips.trip_details', $data);
    }
    // For Trip Details

    // For Create Invoice

    public function create_invoice($id)
    {

        $data['allocation'] = Allocation::find($id);
        $data['trucks'] = TruckAllocation::where('allocation_id', $id)->latest()->get();
        $data['company'] = Company::first();
        // $data[ 'invoiced' ] = InvoicedTruck::where( 'allocation_id', $id )->latest()->get();
        $invoice = TripInvoice::where('allocation_id', $id)->latest()->first();
        $data['payment_methods'] = Ledger::whereBetween('code', [1000, 1099])->whereNot('code', 1000)->orderBy('code', 'asc')->get();

        $data['invoice'] = TripInvoice::where('allocation_id', $id)->latest()->first();
        if ($invoice == null) {
            $data['total'] = 0;
        } else {
            $data['total'] = InvoicedTruck::where('invoice_id', $invoice->id)->latest()->sum('amount');
        }

        return view('finance.trips.create_invoice', $data);
    }

    // For Save Invoice

    public function save_invoice(Request $request)
    {
        $invoice_check = TripInvoice::where('allocation_id', $request->allocation_id)->latest()->first();
        $trip = Trip::where('allocation_id', $request->allocation_id)->first();

        // Case Invoices Exist
        if ($invoice_check) {
            $par = $invoice_check->invoice_ref;
            $raw = explode('/', $par);
            $id = $raw[0];
            $start = $raw[1];
            $new_id = $id . '/' . ($start + 1);
        } else {
            $new_id = $trip->ref_no . '/1';
        }
        $trucks = TruckAllocation::where('allocation_id', $request->allocation_id)->first();

        $real_amount = $request->rate * $request->amount;

        $due_date = date('Y-m-d', strtotime($request->start_date . ' + ' . $trip->allocation->customer->credit_term . ' days'));

        $invoice = new TripInvoice();
        $invoice->allocation_id = $trip->allocation_id;
        $invoice->invoice_ref = $new_id;
        $invoice->start_date = $request->start_date;
        $invoice->due_date = $due_date;
        $invoice->vat = $request->vat;
        $invoice->note = $request->note;

        $invoice->credit_term = $request->credit_term;
        $invoice->type = $request->type;
        $invoice->account = $request->account;

        $invoice->amount = 0;
        $invoice->currency_id = $trucks->allocation->currency->id;
        $invoice->rate = $trucks->allocation->currency->rate;
        $invoice->real_amount = 0;
        $invoice->created_by = Auth::user()->id;

        $invoice->save();

        $id = TripInvoice::where('invoice_ref', $new_id)->first();

        if ($id->type == 1) {
            return redirect(url('/finance/edit-invoice/' . $id->id));
        } else {
            return redirect(url('/finance/edit-other-invoice/' . $id->id));
        }
    }

    // For Update Invoice

    public function update_invoice(Request $request)
    {
        $invoice = TripInvoice::where('id', $request->invoice_id)->latest()->first();
        $trip = Trip::where('allocation_id', $invoice->allocation_id)->first();
        $invoice->start_date = $request->start_date;
        // $due_date = date( 'Y-m-d', strtotime( $request->start_date . ' + ' . $trip->allocation->customer->credit_term . ' days' ) );
        $due_date = $request->due_date;
        $invoice->vat = $request->vat;
        $invoice->note = $request->note;
        $invoice->due_date = $due_date;
        $invoice->update();
        $msg = 'Invoice has been updated successfully!';
        return back()->with('success', $msg);
    }

    // For Correct Invoice

    public function correct_invoice($id)
    {
        $invoice = TripInvoice::where('id', $id)->latest()->first();
        $new_amount = 0;

        $invoiced_trucks = InvoicedTruck::where('invoice_id', $id)->get();
        foreach ($invoiced_trucks as $truck) {
            $new_amount += $truck->amount;
        }

        $new_real_amount = $new_amount * $invoice->currency->rate;

        if ($invoice->status == 2) {

            $description = 'Generated invoice # ' . $invoice->invoice_ref . ' from ' . $invoice->allocations->customer->company;

            // For Updating Transactions
            $transactions = Transaction::where('description', 'LIKE', '%' . $description . '%')->get();

            if (count($transactions) > 0) {

                foreach ($transactions as $transaction) {

                    $transaction->amount = $new_real_amount;
                    $transaction->update();
                }
            } else {

                //Start of Transaction Service
                $client = Ledger::where('customer_id', $invoice->allocations->customer->id)->first();

                $debit_ledger = $client->id;

                // Start of Transaction
                $debitTranscation = new Transaction();
                $debitTranscation->ledger_id = $debit_ledger;
                $debitTranscation->created_at = $invoice->created_at;
                $debitTranscation->amount = $invoice->amount * $invoice->currency->rate;
                $debitTranscation->description =  'Generated invoice # ' . $invoice->invoice_ref . ' from ' . $invoice->allocations->customer->company;
                $debitTranscation->type =  2;
                $debitTranscation->process = 'Invoice Generation';
                $debitTranscation->process_id = $invoice->id;
                $debitTranscation->save();

                $credit_ledger = Ledger::where('code', 4000)->first()->id;
                $creditTranscation = new Transaction();
                $creditTranscation->ledger_id = $credit_ledger;
                $creditTranscation->created_at = $invoice->created_at;
                if ($invoice->vat == 'Yes') {
                    $creditTranscation->amount = ($invoice->amount * $invoice->currency->rate);
                } else {
                    $creditTranscation->amount = $invoice->amount * $invoice->currency->rate;
                }
                $creditTranscation->description =  'Generated invoice # ' . $invoice->invoice_ref . ' from ' . $invoice->allocations->customer->company;
                $creditTranscation->type =  1;
                $creditTranscation->process = 'Invoice Generation';
                $creditTranscation->process_id = $invoice->id;
                $creditTranscation->save();
            }
        }

        $invoice->amount = $new_amount;
        $invoice->real_amount = $new_amount * $invoice->currency->rate;
        $invoice->update();
        $msg = 'Invoice has been Corrected successfully!';
        return back()->with('msg', $msg);
    }

    // For Edit Invoice

    public function edit_invoice($id)
    {
        $invoice = TripInvoice::where('id', $id)->latest()->first();
        $allocation = Allocation::where('id', $invoice->allocation_id)->first();
        $data['allocation'] = Allocation::find($allocation->id);
        $data['trucks'] = TruckAllocation::where('allocation_id', $allocation->id)->latest()->get();
        $data['company'] = Company::first();
        $data['invoice'] = TripInvoice::where('id', $id)->latest()->first();
        $data['payment_methods'] = PaymentMethod::all();
        if ($invoice == null) {
            $data['total'] = 0;
        } else {
            $data['total'] = InvoicedTruck::where('invoice_id', $invoice->id)->latest()->sum('amount');
        }
        return view('finance.trips.edit_invoice', $data);
    }

    // For Other Income Invoice

    public function edit_other_invoice($id)
    {
        $invoice = TripInvoice::where('id', $id)->latest()->first();
        $allocation = Allocation::where('id', $invoice->allocation_id)->first();
        $data['allocation'] = Allocation::find($allocation->id);
        $data['trucks'] = TruckAllocation::where('allocation_id', $allocation->id)->latest()->get();
        $data['company'] = CompanyDetail::first();
        $data['invoice'] = TripInvoice::where('id', $id)->latest()->first();
        if ($invoice == null) {
            $data['total'] = 0;
        } else {
            $data['total'] = InvoicedTruck::where('invoice_id', $invoice->id)->latest()->sum('amount');
        }
        // dd( $data[ 'total' ] );
        return view('finance.trips.edit_other_invoice', $data);
    }
    // For View Invoice

    public function view_invoice($id)
    {
        $uid = Auth::user()->position;
        $process = Approval::where('process_name', 'Invoice Approval')->first();
        $level = ApprovalLevel::where('role_id', $uid)->where('approval_id', $process->id)->first();
        $data['level'] = ApprovalLevel::where('role_id', $uid)->where('approval_id', $process->id)->first();
        $data['final'] = $process->levels;
        $before = ApprovalLevel::where('level_name', (($process->levels) - 1))->first();
        $data['check'] = 'Approved By ' . Auth()->user()->positions->name;
        $invoice = TripInvoice::where('id', $id)->latest()->first();
        $allocation = Allocation::where('id', $invoice->allocation_id)->first();
        $data['allocation'] = Allocation::find($allocation->id);
        $data['trucks'] = TruckAllocation::where('allocation_id', $allocation->id)->latest()->get();
        $data['company'] = CompanyDetail::first();
        $data['invoice'] = TripInvoice::where('id', $id)->latest()->first();
        if ($invoice == null) {
            $data['total'] = 0;
        } else {
            $data['total'] = InvoicedTruck::where('invoice_id', $invoice->id)->latest()->sum('amount');
        }

        // Transaction description
        $description = 'Generated invoice # ' . $invoice->invoice_ref . ' from ' . $invoice->allocations->customer->company;

        $data['transactions'] = Transaction::where('description', 'LIKE', $description)->latest()->get();

        // dd( $invoice );
        return view('finance.trips.view_invoice', $data);
    }

    // For View Other Income Invoice

    public function view_other_invoice($id)
    {
        $uid = Auth::user()->position_id;
        $process = Approval::where('process_name', 'Invoice Approval')->first();
        $level = ApprovalLevel::where('role_id', $uid)->where('approval_id', $process->id)->first();
        $data['level'] = ApprovalLevel::where('role_id', $uid)->where('approval_id', $process->id)->first();
        $data['final'] = $process->levels;
        $before = ApprovalLevel::where('level_name', (($process->levels) - 1))->first();
        $data['check'] = 'Approved By ' . Auth()->user()->position->name;
        $invoice = TripInvoice::where('id', $id)->latest()->first();
        $allocation = Allocation::where('id', $invoice->allocation_id)->first();
        $data['allocation'] = Allocation::find($allocation->id);
        $data['trucks'] = TruckAllocation::where('allocation_id', $allocation->id)->latest()->get();
        $data['company'] = CompanyDetail::first();
        $data['invoice'] = TripInvoice::where('id', $id)->latest()->first();
        if ($invoice == null) {
            $data['total'] = 0;
        } else {
            $data['total'] = InvoicedTruckIncome::where('invoice_id', $invoice->id)->latest()->sum('amount');
        }
        // dd( $invoice );
        return view('finance.trips.view_other_invoice', $data);
    }

    // For Print Invoice

    public function print_invoice($id)
    {
        $invoice = TripInvoice::where('id', $id)->latest()->first();
        $allocation = Allocation::where('id', $invoice->allocation_id)->first();
        $data['allocation'] = Allocation::find($allocation->id);
        $data['trucks'] = TruckAllocation::where('allocation_id', $allocation->id)->latest()->get();
        $data['company'] = CompanyDetail::first();
        $data['invoice'] = TripInvoice::where('id', $id)->latest()->first();
        if ($invoice == null) {
            $data['total'] = 0;
        } else {
            $data['total'] = InvoicedTruck::where('invoice_id', $invoice->id)->latest()->sum('amount');
        }

        $pdf = FacadePdf::loadView('finance.trips.print_invoice', $data);
        return $pdf->download();
    }

    // For Print other income invoice

    public function print_other_invoice($id)
    {
        $invoice = TripInvoice::where('id', $id)->latest()->first();
        $allocation = Allocation::where('id', $invoice->allocation_id)->first();
        $data['allocation'] = Allocation::find($allocation->id);
        $data['trucks'] = TruckAllocation::where('allocation_id', $allocation->id)->latest()->get();
        $data['company'] = CompanyDetail::first();
        // $data[ 'invoiced' ] = InvoicedTruck::where( 'allocation_id', $id )->latest()->get();
        $data['invoice'] = TripInvoice::where('id', $id)->latest()->first();
        if ($invoice == null) {
            $data['total'] = 0;
        } else {
            $data['total'] = InvoicedTruckIncome::where('invoice_id', $invoice->id)->latest()->sum('amount');
        }
        // return view( 'finance.trips.print_other_invoice', $data );

        $pdf = FacadePdf::loadView('finance.trips.print_other_invoice', $data);
        return $pdf->download();
    }

    // For Invoice Deletion

    public function delete_invoice($id)
    {
        $invoice = TripInvoice::find($id);

        $invoiced_trucks = InvoicedTruck::where('invoice_id', $invoice->id)->get();
        foreach ($invoiced_trucks as $invoiced) {
            $invoiced->delete();
        }
        $invoice->delete();

        $msg = 'Trip Invoice has been cancelled Successfully !';
        return back()->with('msg', $msg);
    }

    // For Invoice Submission

    public function submit_invoice($id)
    {
        $invoice = TripInvoice::find($id);
        $invoice->status = 1;
        $invoice->update();

        // For Email Notification to the next level
        $deptID = Auth::user()->department;
        $department = Department::where('id', $deptID)->first();

        if ($department->hod != Null) {
            $employees = User::where('emp_id', $department->hod)->get();

            $email_data = array(
                'subject' => 'Invoice Approval',
                'view' => 'emails.invoices.hod-approval',
                'invoice' => $invoice,

            );
            $job = (new \App\Jobs\SendEmail($email_data, $employees));
            dispatch($job);
        }

        // end of Approval Email Alert

        $msg = 'Trip Invoice has been Submitted Successfully !';
        return back()->with('msg', $msg);
    }
    // For Add Truck to Invoice

    public function add_invoiced_truck(Request $request)
    {

        request()->validate(
            [
                'truck_id' => 'required',
                'allocation_id' => 'required',
                'ref_no' => 'required',
                'invoice_id' => 'required'

            ]
        );

        $invoice_check = TripInvoice::where('id', $request->invoice_id)->latest()->first();
        $trip = Trip::where('allocation_id', $request->allocation_id)->first();

        $trucks = TruckAllocation::where('allocation_id', $request->allocation_id)->where('truck_id', $request->truck_id)->first();

        $new_amount = $invoice_check->amount + ($request->amount * $request->quantity);
        $invoiced_income = $invoice_check->amount;
        $new_invoiced_income = $invoiced_income + ($request->invoice_rate * $request->quantity);

        $trip->invoiced_income = $new_invoiced_income;
        $trip->update();
        $new_real_amount = $invoice_check->real_amount + ($request->real_amount * $invoice_check->rate);
        $invoice = new InvoicedTruck();
        $invoice->truck_id = $trucks->truck_id;
        $invoice->allocation_id = $trucks->id;
        $invoice->invoice_id = $invoice_check->id;
        $invoice->ref_no = $request->ref_no;
        $invoice->truck_id = $request->truck_id;
        $invoice->amount = ($request->invoice_rate * $request->quantity);
        $invoice->quantity = $request->quantity;
        $invoice->currency_id = $request->currency_id;
        $invoice->rate = $request->rate;
        $invoice->invoice_rate = $request->invoice_rate;
        $invoice->real_amount = $request->rate * ($request->invoice_rate * $request->quantity);
        $invoice->created_by = Auth::user()->id;

        $truck_allocation = TruckAllocation::where('allocation_id', $trip->allocation_id)->where('truck_id', $request->truck_id)->first();
        $truck = InvoicedTruck::where('invoice_id', $invoice_check->id)->where('truck_id', $request->truck_id)->first();
        $inv_total = ($request->invoice_rate * $request->quantity);
        // Start of uninvoiced Trucks
        if ($truck == null) {
            // dd( 'wait' );
            $old_invoice = TripInvoice::where('allocation_id', $trip->allocation_id)->first();
            $old_invoice_amount = InvoicedTruck::where('invoice_id', $old_invoice->id)->where('truck_id', $request->truck_id)->sum('amount');
            $truck_income = $truck_allocation->income / $truck_allocation->allocation->currency->rate;
            $new_amount = $old_invoice_amount + ($request->quantity * $request->invoice_rate);
            // if ( number_format( $new_amount, 2 )  <= number_format( $truck_income, 2 ) ) {

            $invoice->save();
            $invoice_check->amount =  $invoice_check->amount + ($request->invoice_rate * $request->quantity);
            $invoice_check->real_amount =  $invoice_check->real_amount + (($request->invoice_rate * $request->quantity) * $invoice_check->rate);
            $invoice_check->update();
            // } else {

            //     $error = 'The Amount Entered is greater than the required  !';
            //     return back()->with( 'error', $error );
            // }
        }
        // Start of Already Invoiced Trucks
        else {
            $payment = InvoicedTruck::where('invoice_id', $invoice_check->id)->where('truck_id', $request->truck_id)->sum('amount');
            $charges = InvoicedTruck::where('invoice_id', $invoice_check->id)->where('truck_id', $request->truck_id)->sum('charges');

            $balance = $payment + $charges;

            $new_balance = $balance + ($request->invoice_rate * $request->quantity);
            // if ( number_format( $new_balance, 2 ) <= number_format( ( $truck_allocation->income / $truck_allocation->allocation->currency->rate ), 2 ) ) {

            $invoice->save();
            $invoice_check->amount = $invoice_check->amount + ($request->invoice_rate * $request->quantity);
            $invoice_check->real_amount = $invoice_check->real_amount + (($request->invoice_rate * $request->quantity) * $invoice_check->rate);
            $invoice_check->update();
            // } else {
            //     $error = 'The Amount Entered is greater than the required !';
            //     return back()->with( 'error', $error );
            // }
        }

        $msg = 'Truck Added Successfully';
        return back()->with('msg', $msg);
    }

    // For Add Truck to Invoice

    public function add_invoiced_truck_income(Request $request)
    {

        request()->validate(
            [
                'truck_id' => 'required',
                'allocation_id' => 'required',
                'invoice_id' => 'required'

            ]
        );

        $invoice_check = TripInvoice::where('id', $request->invoice_id)->latest()->first();
        // dd( $request->invoice_id );
        $trip = Trip::where('allocation_id', $request->allocation_id)->first();

        $invoiced_income = $trip->invoiced_income;

        $trucks = TruckAllocation::where('allocation_id', $request->allocation_id)->where('truck_id', $request->truck_id)->first();

        $new_amount = $invoice_check->amount + $request->amount;
        $invoice = new InvoicedTruckIncome();
        $invoice->truck_id = $trucks->truck_id;
        $invoice->allocation_id = $trucks->id;
        $invoice->invoice_id = $invoice_check->id;
        $invoice->income_id = $request->income_id;
        $invoice->truck_id = $request->truck_id;
        $invoice->amount = $request->amount;
        $invoice->currency_id = $request->currency_id;
        $currency = Currency::where('id', $invoice_check->currency_id)->first();
        $invoice->rate = $currency->rate;
        $invoice->real_amount = $request->amount * $currency->rate;
        $invoice->created_by = Auth::user()->id;
        $invoice->save();

        $invoice_check->amount = $new_amount;
        $invoice_check->real_amount = $new_amount * $currency->rate;
        $invoice_check->update();

        $new_invoiced_income = $invoiced_income + $request->amount;

        $trip->invoiced_income = $new_invoiced_income;
        $trip->update();

        $msg = 'Other Income Added Successfully';
        return back()->with('msg', $msg);
    }

    // For delete Truck to Invoice

    public function delete_invoiced_truck($id)
    {
        $invoice = InvoicedTruck::find($id);
        $reduced_amount = $invoice->amount;
        $reduced_real_amount = $invoice->real_amount;
        $trip_invoice = TripInvoice::where('id', $invoice->invoice_id)->first();
        $current_amount = $trip_invoice->amount;
        $new_current_amount = $current_amount - $reduced_amount;
        $real_amount = $trip_invoice->real_amount;
        $new_real_amount = $real_amount - $reduced_real_amount;
        $trip_invoice->amount = $new_current_amount;
        $trip_invoice->real_amount = $new_real_amount;
        $trip_invoice->update();
        $invoice->delete();

        $trip = Trip::where('allocation_id',  $trip_invoice->allocation_id)->first();
        $invoiced_income = $trip->invoiced_income;
        $new_invoiced_income = $invoiced_income - $invoice->amount;

        $trip->invoiced_income = $new_invoiced_income;
        $trip->update();

        $msg = 'Invoiced Truck has been removed Successfully !';
        return back()->with('msg', $msg);
    }

    // For delete Truck to Invoice

    public function delete_invoiced_truck_income($id)
    {
        $invoice = InvoicedTruckIncome::find($id);
        $reduced_amount = $invoice->amount;
        $reduced_real_amount = $invoice->real_amount;
        $trip_invoice = TripInvoice::where('id', $invoice->invoice_id)->first();
        $current_amount = $trip_invoice->amount;
        $new_current_amount = $current_amount - $reduced_amount;
        $real_amount = $trip_invoice->real_amount;
        $new_real_amount = $real_amount - $reduced_real_amount;
        $trip_invoice->amount = $new_current_amount;
        $trip_invoice->real_amount = $new_real_amount;
        $trip_invoice->update();
        $invoice->delete();

        $msg = 'Invoiced Truck has been removed Successfully !';
        return back()->with('msg', $msg);
    }

    // For Approve Invoice


    public function approve_invoice(Request $request)
    {
        DB::beginTransaction(); // Start transaction

        try {
            $role_id = Auth::User()->position_id;
            $terminate = Approval::where('process_name', 'Invoice Approval')->first();
            $roles = Position::where('id', $role_id)->first();
            $level = ApprovalLevel::where('role_id', $role_id)->where('approval_id', $terminate->id)->first();

            if ($level) {
                $approval_id = $level->approval_id;
                $approval = Approval::where('id', $approval_id)->first();

                if ($approval->levels == $level->level_name) {

                    $invoice = TripInvoice::where('id', $request->allocation_id)->first();
                    $id = Trip::where('allocation_id', $invoice->allocation_id)->first();
                    $trip = Allocation::where('id', $id->allocation_id)->first();

                    $remark = new TripInvoiceRemark();
                    $remark->invoice_id = $request->allocation_id;
                    $remark->remark = $request->reason;
                    $remark->remarked_by = $roles->name;
                    $remark->created_by = Auth::user()->id;
                    $remark->save();

                    // Start of Transaction Service
                    $client = Ledger::where('customer_id', $id->allocation->customer->id)->first();
                    $debit_ledger = $client->id;

                    // For Currency Log
                    $currencyLog = CurrencyLog::latest()->first();
                    if ($currencyLog == null) {
                        throw new \Exception('Sorry, There is no currency Log!');
                    }

                    // Start of Transaction
                    $debitTranscation = new Transaction();
                    $debitTranscation->created_at = $invoice->start_date;
                    $debitTranscation->ledger_id = $debit_ledger;
                    $debitTranscation->amount = $invoice->amount * $invoice->currency->rate;
                    $debitTranscation->description = 'Generated invoice # ' . $invoice->invoice_ref . ' from ' . $trip->customer->company;
                    $debitTranscation->type = 2;
                    $debitTranscation->process = 'Invoice Generation';
                    $debitTranscation->process_id = $invoice->id;
                    $debitTranscation->currency_log_id = $currencyLog->id;
                    $debitTranscation->save();

                    if ($invoice->type == 1) {
                        $credit_ledger = LedgerAccount::where('code', 4000)->first()->id;
                        $creditTranscation = new Transaction();
                        $creditTranscation->ledger_id = $credit_ledger;
                        $creditTranscation->amount = $invoice->amount * $invoice->currency->rate;
                        $creditTranscation->created_at = $invoice->start_date;
                        $creditTranscation->description = 'Generated invoice # ' . $invoice->invoice_ref . ' from ' . $trip->customer->company;
                        $creditTranscation->type = 1;
                        $creditTranscation->process = 'Invoice Generation';
                        $creditTranscation->process_id = $invoice->id;
                        $creditTranscation->currency_log_id = $currencyLog->id;
                        $creditTranscation->save();
                    } else {
                        $credit_ledger = LedgerAccount::where('code', 4500)->first()->id;
                        $creditTranscation = new Transaction();
                        $creditTranscation->created_at = $invoice->start_date;
                        $creditTranscation->ledger_id = $credit_ledger;
                        $creditTranscation->amount = $invoice->amount * $invoice->currency->rate;
                        $creditTranscation->description = 'Generated invoice # ' . $invoice->invoice_ref . ' from ' . $trip->customer->company;
                        $creditTranscation->type = 1;
                        $creditTranscation->process = 'Invoice Generation';
                        $creditTranscation->process_id = $invoice->id;
                        $creditTranscation->currency_log_id = $currencyLog->id;
                        $creditTranscation->save();
                    }

                    if ($invoice->vat == 'Yes') {
                        // Start of Tax Transaction
                        $tax_ledger = LedgerAccount::where('code', '2200')->first()->id;
                        $tax_amount = ($invoice->amount * $invoice->currency->rate) * (18 / 100);
                        $taxTranscation = new Transaction();
                        $taxTranscation->created_at = $invoice->start_date;
                        $taxTranscation->ledger_id = $tax_ledger;
                        $taxTranscation->amount = $tax_amount;
                        $taxTranscation->description = 'Received Tax Payment from invoice # ' . $invoice->invoice_ref . ' from ' . $trip->customer->company;
                        $taxTranscation->type = 2;
                        $taxTranscation->currency_log_id = $currencyLog->id;
                        $taxTranscation->process = 'Invoice Generation';
                        $taxTranscation->process_id = $invoice->id;
                        $taxTranscation->save();
                        // End of Tax Transaction
                    }

                    $invoice->status = 2;
                    $invoice->currency_log_id = $currencyLog->id;
                    $invoice->update();

                    DB::commit(); // Commit transaction
                    $msg = 'Trip Invoice request has been approved Successfully!';
                    return back()->with('msg', $msg);
                } else {
                    // For Currency Log
                    $currencyLog = CurrencyLog::latest()->first();
                    if ($currencyLog == null) {
                        throw new \Exception('Sorry, There is no currency Log!');
                    }

                    // To be upgraded
                    $invoice = TripInvoice::where('id', $request->allocation_id)->first();
                    $invoice->state = 'Approved By ' . $roles->name;
                    $invoice->status = 1;
                    $invoice->currency_log_id = $currencyLog->id;
                    $invoice->update();

                    $remark = new TripInvoiceRemark();
                    $remark->invoice_id = $request->allocation_id;
                    $remark->remark = $request->reason;
                    $remark->remarked_by = $roles->name;
                    $remark->created_by = Auth::user()->id;
                    $remark->save();

                    DB::commit(); // Commit transaction
                    $msg = 'Approved By ' . $roles->name;
                    return back()->with('msg', $msg);
                }
            } else {
                $msg = 'Failed To Approve!';
                return back()->with('msg', $msg);
            }
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction
            return back()->with('error', $e->getMessage());
        }
    }

    // For Disapprove Invoice

    public function disapprove_invoice(Request $request)
    {

        $role_id = Auth::User()->position_id;
        $roles = Position::where('id', $role_id)->first();
        $allocation = TripInvoice::where('id', $request->allocation_id)->first();
        $allocation->status = -1;

        $remark = new TripInvoiceRemark();
        $remark->invoice_id = $request->allocation_id;
        $remark->remark = $request->reason;
        $remark->remarked_by = $roles->name;
        $remark->created_by = Auth::user()->id;
        $remark->save();

        $allocation->update();

        return back()->with('Allocation has been Disapproved Successfully !');
    }

    // For All Trip Invoices

    public function all_invoices()
    {
        $uid = Auth::user()->position_id;
        $process = Approval::where('process_name', 'Invoice Approval')->first();
        $data['level'] = ApprovalLevel::where('role_id', $uid)->where('approval_id', $process->id)->first();
        $data['pending'] = TripInvoice::where('status', '2')->latest()->get();
        $data['completed'] = Trip::where('state', 5)->latest()->get();
        $data['check'] = 'Approved By ' . Auth()->user()->positions->name;
        $data['completed_trips'] = Trip::where('status', 0)->latest()->count();
        $data['total_trips'] = Trip::count();
        $data['active_trips'] = Trip::where('status', 1)->latest()->count();
        $data['trip_requests'] = TripInvoice::where('status', 2)->orWhere('status', '3')->latest()->count();
        $data['payment_methods'] = LedgerAccount::whereBetween('code', [1000, 1099])->whereNot('code', 1000)->orderBy('code', 'asc')->get();

        return view('finance.trip-invoices.index', $data);
    }

    // For Single Invoice Payment

    public function single_invoice($id)
    {
        $uid = Auth::user()->position_id;
        $process = Approval::where('process_name', 'Invoice Approval')->first();
        $level = ApprovalLevel::where('role_id', $uid)->where('approval_id', $process->id)->first();
        $data['level'] = ApprovalLevel::where('role_id', $uid)->where('approval_id', $process->id)->first();
        $data['final'] = $process->levels;
        $before = ApprovalLevel::where('level_name', (($process->levels) - 1))->first();
        $data['check'] = 'Approved By ' . Auth()->user()->positions->name;
        $invoice = TripInvoice::where('id', $id)->latest()->first();
        $allocation = Allocation::where('id', $invoice->allocation_id)->first();
        $data['allocation'] = Allocation::find($allocation->id);
        $data['trucks'] = TruckAllocation::where('allocation_id', $allocation->id)->latest()->get();
        $data['company'] = CompanyDetail::first();
        // $data[ 'invoiced' ] = InvoicedTruck::where( 'allocation_id', $id )->latest()->get();
        $data['invoice'] = TripInvoice::where('id', $id)->latest()->first();
        $data['payments'] = TripInvoicePayment::where('invoice_id', $invoice->id)->latest()->get();
        // $data[ 'payment_methods' ] = LedgerAccount::whereBetween( 'code', [ 1000, 1099 ] )->whereNot( 'code', 1000 )->orderBy( 'code', 'asc' )->get();

        // Transaction description
        $description = 'Received Trip Payment for Invoince# ' . $invoice->invoice_ref . ' from ' . $allocation->customer->company;

        $data['transactions'] = Transaction::where('description', 'LIKE', $description)->latest()->get();

        $data['payment_methods'] = PaymentMethod::all();
        if ($invoice == null) {
            $data['total'] = 0;
        } else {
            $data['total'] = InvoicedTruck::where('invoice_id', $invoice->id)->latest()->sum('amount');
        }
        return view('finance.trip-invoices.single_invoice', $data);
    }

    // For Bulk Invoice Payment Page

    public function bulk_invoice_payment()
    {
        $data['invoices'] = TripInvoice::where('status', '2')->latest()->get();
        $data['currencies'] = Currency::get();
        $data['payment_methods'] = PaymentMethod::orderBy('id', 'desc')->get();
        return view('finance.trip-invoices.bulk', $data);
    }

    // For Save Bulk Payment

    function save_bulk_invoice_payment(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'credit_ledger' => 'required',
            'currency_id' => 'required',
            // 'reason' => 'required',
            'moreFields.*.id' => 'required',
            // 'moreFields.*.credit_ledger' => 'nullable',
            'moreFields.*.amount' => 'required',
            'moreFields.*.charges' => 'nullable',
            // 'moreFields.*.currency_id' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->errors()->all(),
            ]);
        } else {

            // For Currency Log
            $currencyLog = CurrencyLog::latest()->first();
            if ($currencyLog == null) {
                throw new \Exception('Sorry, There is no currency Log!');
            }

            foreach ($request->moreFields as $key => $value) {

                $paid = TripInvoicePayment::where('invoice_id', $value['id'])->sum('amount');
                $invoice = TripInvoice::where('id', $value['id'])->first();
                $new_total = $paid + $value['amount'];
                // if ( $new_total > $invoice->amount ) {
                //     return back()->with(
                //         // 'status', 400,
                //         'errors',
                //         'Amount Entered in ' . $invoice->invoice_ref . 'exceeds the Invoiced Amount'
                // );
                // }
                $payment = new TripInvoicePayment();
                $payment->invoice_id = $value['id'];
                $payment->amount = $value['amount'];
                $payment->paid_date = $request->paid_date;
                $currency = Currency::where('id', $request->currency_id)->first();
                $payment->real_amount = $value['amount'] * $invoice->currency->rate;
                $payment->remaining = $value['amount'];
                $payment->real_remaining = $value['amount'];
                // $currency = Currency::where( 'id', $request->currency_id )->first();
                $payment->currency_id = $request->currency_id;
                $payment->rate = $currency->rate;
                $payment->created_by = Auth::user()->id;
                $payment->payment_method = $request->credit_ledger;
                $payment->currency_log_id = $currencyLog->id;
                $payment->charges = $value['charges'];

                $payment->save();
                $invoice = TripInvoice::where('id',  $value['id'])->latest()->first();
                $allocation = Allocation::where('id', $invoice->allocation_id)->first();

                //Start of Transaction Service
                $client = LedgerAccount::where('customer_id', $allocation->customer->id)->first();
                $debit_ledger = $request->credit_ledger;
                $credit_ledger = $client->id;

                $creditTranscation = new Transaction();
                $creditTranscation->ledger_id = $credit_ledger;
                $creditTranscation->created_at = $request->paid_date;

                // For Bank Charges
                $creditTranscation->amount = ($value['amount'] + $value['charges']) * $invoice->currency->rate;
                $creditTranscation->amount = ($value['amount']) * $invoice->currency->rate;

                $creditTranscation->description =  'Received Trip Payment for Invoince# ' . $invoice->invoice_ref . ' from ' . $allocation->customer->company;
                $creditTranscation->type =  1;
                $creditTranscation->process = 'Trip Invoice Payment';
                $creditTranscation->process_id = $invoice->id;
                $creditTranscation->created_by = Auth::user()->id;
                $creditTranscation->currency_log_id = $currencyLog->id;
                $creditTranscation->save();

                $debitTranscation = new Transaction();
                $debitTranscation->ledger_id = $debit_ledger;
                $debitTranscation->created_at = $request->paid_date;

                // $debitTranscation->amount = ( ( $value[ 'amount' ] - $value[ 'charges' ] ) ) * $invoice->currency->rate;
                $debitTranscation->amount = (($value['amount'])) * $invoice->currency->rate;
                $debitTranscation->description =  'Received Trip Payment for Invoince# ' . $invoice->invoice_ref . ' from ' . $allocation->customer->company;
                $debitTranscation->process = 'Trip Invoice Payment';
                $debitTranscation->process_id = $invoice->id;
                $debitTranscation->type =  2;
                $debitTranscation->created_by = Auth::user()->id;
                $debitTranscation->currency_log_id = $currencyLog->id;
                $debitTranscation->save();

                // For Service Charges
                $charges = LedgerAccount::where('name', 'like', '%Service Charges%')->first();
                if ($charges) {
                    $chargesTranscation = new Transaction();
                    $chargesTranscation->ledger_id = $charges->id;
                    $chargesTranscation->amount = ($value['charges']) * $invoice->currency->rate;
                    $chargesTranscation->description =  'Paid Service Charges for Invoince# ' . $invoice->invoice_ref . ' from ' . $allocation->customer->company;
                    $chargesTranscation->type =  2;
                    $chargesTranscation->process = 'Trip Invoice Payment';
                    $chargesTranscation->process_id = $invoice->id;
                    $chargesTranscation->created_by = Auth::user()->id;
                    $chargesTranscation->category = "General Revenue";
                    $chargesTranscation->currency_log_id = $currencyLog->id;
                    $chargesTranscation->save();
                }
            }

            return response()->json([
                'status' => 200,
                'route_truck' => route('finance.all-trip-invoices'),
                'errors' => 'Payment Received',
            ]);
        }
    }

    // Reverse Deleted Transactions

    public function reverse_deleted_transactions($id)
    {
        $invoice = TripInvoice::where('id', $id)->latest()->first();

        $payments = TripInvoicePayment::where('invoice_id', $id)->count();

        if ($payments == 0) {

            // For Direct Expense Transactions
            $description = 'Received Trip Payment for Invoince# ' . $invoice->invoice_ref . ' from ' . $invoice->allocations->customer->company;
            $transaction = Transaction::where('description', 'LIKE', $description)->where('type', 1)->first();
            $transaction2 = Transaction::where('description', 'LIKE', $description)->where('type', 2)->first();

            $charges_description = 'Paid Service Charges for Invoince# ' . $invoice->invoice_ref . ' from ' . $invoice->allocations->customer->company;
            $charges_transaction = Transaction::where('description', 'LIKE', $charges_description)->where('type', 2)->first();

            if ($transaction) {

                $payment = new TripInvoicePayment();
                $payment->invoice_id = $invoice->id;
                $payment->amount = $transaction->amount / $invoice->currency->rate;
                $payment->paid_date = $transaction->created_at;
                $payment->real_amount = $transaction2->amount;
                $payment->remaining = $invoice->amount - ($transaction->amount / $invoice->currency->rate);
                $payment->real_remaining = $invoice->real_amount - $transaction->amount;
                $payment->currency_id = $invoice->currency_id;
                $payment->rate = $invoice->currency->rate;
                $payment->created_by = Auth::user()->id;
                $payment->payment_method = $transaction->ledger_id;
                $payment->charges =  ($transaction->amount - $transaction2->amount) / $invoice->currency->rate;
                $payment->save();
            }
        }
        // else
        // {
        //     $trip_payments = TripInvoicePayment::where( 'invoice_id', $id )->get();
        //     foreach ( $trip_payments as $trip_payment ) {

        //         $description = 'Received Trip Payment for Invoince# ' . $invoice->invoice_ref . ' from ' . $invoice->allocations->customer->company;
        //         $transaction = Transaction::where( 'description', 'LIKE', $description )->where( 'type', 1 )->where( 'process_id', $trip_payment->id )->where( 'process', 'Trip Invoice Receipt' )->first();

        //         $charges_description = 'Paid Service Charges for Invoince# ' . $invoice->invoice_ref . ' from ' . $invoice->allocations->customer->company;
        //         $charges_transaction = Transaction::where( 'description', 'LIKE', $charges_description )->where( 'type', 2 )->where( 'process_id', $trip_payment->id )->where( 'process', 'Trip Invoice Receipt' )->first();

        //         if ( $transaction ) {

        //             $payment = new TripInvoicePayment();
        //             $payment->invoice_id = $invoice->id;
        //             $payment->amount = $transaction->amount/ $invoice->currency->rate;
        //             $payment->paid_date = $transaction->created_at;
        //             $payment->real_amount = $transaction->amount ;
        //             $payment->remaining = $invoice->amount - ( $transaction->amount/ $invoice->currency->rate );
        //             $payment->real_remaining = $invoice->real_amount-$transaction->amount;
        //             $payment->currency_id = $invoice->currency_id;
        //             $payment->rate = $invoice->currency->rate;
        //             $payment->created_by = Auth::user()->id;
        //             $payment->payment_method = $transaction->ledger_id;
        //             $payment->charges =  $charges_transaction / $invoice->currency->rate;
        //             $payment->save();

        //         }

        //     }
        // }

        // For Payment Log
        SystemLogHelper::logSystemActivity('Trip Payment Deletion Receipt', auth()->user()->id, auth()->user()->fname . ' ' . auth()->user()->lname . ' has reversed the deleted payment for Trip Invoice :' . $invoice->invoice_ref);

        // end of Transaction
        return back();
    }

    // For Trip Invoice Payment

    public function trip_invoice_payment(Request $request)
    {

        // For Currency Log
        $currencyLog = CurrencyLog::latest()->first();
        if ($currencyLog == null) {
            throw new \Exception('Sorry, There is no currency Log!');
        }

        $payment = new TripInvoicePayment();
        $invoice = TripInvoice::where('id', $request->id)->first();
        $payment->invoice_id = $request->id;
        $payment->amount = $request->amount;
        $payment->paid_date = $request->paid_date;
        $currency = Currency::where('id', $request->currency_id)->first();
        $payment->real_amount = $request->amount * $invoice->currency->rate;
        $payment->remaining = $request->amount;
        $payment->real_remaining = $request->amount;
        // $currency = Currency::where( 'id', $request->currency_id )->first();
        $payment->currency_id = $request->currency_id;
        $payment->rate = $currency->rate;
        $payment->created_by = Auth::user()->id;
        $payment->payment_method = $request->credit_ledger;
        $payment->charges = $request->charges;


        $payment->currency_log_id = $currencyLog->id;
        $payment->save();

        $invoice = TripInvoice::where('id',  $request->id)->latest()->first();
        $allocation = Allocation::where('id', $invoice->allocation_id)->first();
        // //Start of Transaction Service

        //Start of Transaction Service
        $client = LedgerAccount::where('customer_id', $allocation->customer->id)->first();
        $debit_ledger = $request->credit_ledger;
        $credit_ledger = $client->id;

        $creditTranscation = new Transaction();
        $creditTranscation->ledger_id = $credit_ledger;
        $creditTranscation->amount = ($request->amount + $request->charges) * $invoice->currency->rate;
        $creditTranscation->description =  'Received Trip Payment for Invoince# ' . $invoice->invoice_ref . ' from ' . $allocation->customer->company;
        $creditTranscation->type =  1;
        $creditTranscation->process = 'Trip Invoice Receipt';
        $creditTranscation->process_id = $payment->id;
        $creditTranscation->currency_log_id = $currencyLog->id;
        $creditTranscation->save();

        $debitTranscation = new Transaction();
        $debitTranscation->ledger_id = $debit_ledger;
        $debitTranscation->amount = ($request->amount) * $invoice->currency->rate;
        $debitTranscation->process = 'Trip Invoice Receipt';
        $debitTranscation->process_id = $payment->id;
        $debitTranscation->description =  'Received Trip Payment for Invoince# ' . $invoice->invoice_ref . ' from ' . $allocation->customer->company;
        $debitTranscation->type =  2;
        $debitTranscation->currency_log_id = $currencyLog->id;
        $debitTranscation->save();

        // For Service Charges
        $charges = LedgerAccount::where('name', 'like', '%SERVICE CHARGES%')->first();
        if ($charges) {
            $chargesTranscation = new Transaction();
            $chargesTranscation->ledger_id = $charges->id;
            $chargesTranscation->amount = ($request->charges) * $invoice->currency->rate;
            $chargesTranscation->description =  'Paid Service Charges for Invoince# ' . $invoice->invoice_ref . ' from ' . $allocation->customer->company;
            $chargesTranscation->type =  2;
            $chargesTranscation->process = 'Invoice Receipt';
            $chargesTranscation->process_id = $invoice->id;
            $chargesTranscation->currency_log_id = $currencyLog->id;
            $chargesTranscation->save();
        }

        // For Payment Log
        SystemLogHelper::logSystemActivity('Trip Receipt Payment', auth()->user()->id, auth()->user()->fname . ' ' . auth()->user()->lname . ' has received payment for Trip Invoice :' . $invoice->invoice_ref);

        // end of Transaction
        return back();
    }

    // For Update Trip Invoice Payment

    public function update_invoice_payment(Request $request)
    {
        # code...
    }

    // For Update Trip Invoice Payment

    public function delete_invoice_payment($id)
    {
        $invoice = TripInvoicePayment::find($id);
        $amount = $invoice->real_amount;
        $charged_amount = $invoice->amount * $invoice->currency->rate;

        $description = 'Received Trip Payment for Invoince# ' . $invoice->invoice->invoice_ref . ' from ' . $invoice->invoice->allocations->customer->company;
        $transactions = Transaction::where('description', 'LIKE', $description)->where('amount', $amount)->get();
        foreach ($transactions as $transaction) {

            $transaction->delete();
        }

        $charges_description = 'Paid Service Charges for Invoince# ' . $invoice->invoice->invoice_ref . ' from ' . $invoice->invoice->allocations->customer->company;
        $transactions = Transaction::where('description', 'LIKE', $charges_description)->where('type', 1)->where('amount', $charged_amount)->first();

        if ($transactions) {
            $transactions->delete();
        }

        $invoice->delete();
        $msg = 'Tip Invoice Payment Was Deleted Successfully !';
        return back()->with('msg', $msg);
    }

    function numberToWords($number)
    {
        // $numberFormatter = new NumberFormatter();
        $formatter = new NumberFormatter('en', NumberFormatter::SPELLOUT);
        $numberFormat = '#.##';
        // This is just an example using US dollars as currency. You can use different formats based on your requirements.

        // Convert the number to its textual representation
        // $text = $numberFormatter->toWords( $number );
        return $formatter->format($number);

        // return $text;
    }
    // For Trip Invoice Payment

    public function print_invoice_payment($id)
    {
        $data['payment'] = TripInvoicePayment::find($id);
        $payment = TripInvoicePayment::find($id);
        $invoice = TripInvoice::where('id', $payment->invoice_id)->latest()->first();
        $allocation = Allocation::where('id', $invoice->allocation_id)->first();
        $data['allocation'] = Allocation::find($allocation->id);
        $data['trucks'] = TruckAllocation::where('allocation_id', $allocation->id)->latest()->get();
        $data['company'] = CompanyDetail::first();
        $data['invoice'] = TripInvoice::where('id', $payment->invoice_id)->latest()->first();
        $data['payments'] = TripInvoicePayment::where('invoice_id', $invoice->id)->latest()->get();
        // $data[ 'word' ] = $this->numberToWords( $payment->amount );
        $data['word'] = '';

        // return view( 'finance.trip-invoices.payment-receipt', $data );

        $pdf = FacadePdf::loadView('finance.trip-invoices.payment-receipt', $data);

        // Set the filename and download the PDF
        $filename = 'payment_receipt_' . $invoice->invoice_ref . '.pdf';
        return $pdf->download($filename);
    }

    // For Customer Invoices

    public function customer_invoice()
    {
        $uid = Auth::user()->position_id;
        $process = Approval::where('process_name', 'Invoice Approval')->first();
        $data['level'] = ApprovalLevel::where('role_id', $uid)->where('approval_id', $process->id)->first();
        $data['final'] = $process->levels;

        $data['invoices'] = CustomerInvoice::latest()->where('approval_status', 0)->orWhere('status', -1)->get();
        $data['pending'] = CustomerInvoice::latest()->where('approval_status', '>', 0)->where('status', 1)->get();
        $data['approved'] = CustomerInvoice::latest()->where('status', 2)->get();

        return view('finance.customer_invoice.index', $data);
    }

    // For Create Customer Invoice

    public function create_customer_invoice()
    {

        $data['customers'] = Customer::get();
        $data['trucks'] = Truck::get();
        $data['currencies'] = Currency::get();
        $data['accounts'] = PaymentMethod::get();
        $data['routes'] = Route::get();

        return view('finance.customer_invoice.create', $data);
    }
}