<?php

namespace App\Http\Controllers\Accounting;

use App\Models\Ledger;
use App\Models\Currency;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Account\ProcessLedger;
use App\Models\Account\ProcessLedgerMapper;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Current;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('ledger')->latest()->paginate(100);
        $data['transactions'] = $transactions;

        return view('accounts.transactions.index', $data);
    }

    public function create()
    {
        $data['ledgers'] = Ledger::all();
        return view('accounts.transactions.create', $data);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'debit_ledger_id' => 'required|',
            'credit_ledger_id' => 'required',
            'amount' => 'required|numeric'
        ]);


        $currency = Currency::where('id', $request->currency_id)->first();

        // $inputs = $request->all();

        $inputs['amount'] = $request->amount * $currency->rate;
        $inputs['description'] = $request->description;
        $inputs['type'] = 2;
        $inputs['ledger_id'] = $request->debit_ledger_id;
        Transaction::create($inputs);

        $inputs['type'] = 1;
        $inputs['ledger_id'] = $request->credit_ledger_id;
        Transaction::create($inputs);



        return redirect('account/transaction')->with('msg', 'Transaction was Entered Successfully !');
    }

    public function show($id)
    {
        $transaction = Transaction::find($id);

        $transactions_data = [
            (object) [
                "name" => "Ledger Name",
                "value" =>  $transaction->ledger->name ?? ''
            ],
            (object) [
                "name" => "Code",
                "value" => $transaction->ledger->client_code ?? ''
            ],
            (object) [
                "name" => "Amount",
                "value" => $transaction->amount ?? ''
            ],
            (object) [
                "name" => "Type of Accounting",
                "value" => $transaction->type ?? ""
            ],
            (object) [
                "name" => "Description",
                "value" => $transaction->description ?? ""
            ],
            (object) [
                "name" => "Process",
                "value" => $transaction->process ?? ""
            ],
            (object) [
                "name" => "Created by",
                "value" => $transaction->created_by ?? ""
            ]
        ];
        $data['transactions_data'] = $transactions_data;

        return view('accounts.transactions.show', $data);
    }

    public function edit($id)
    {
        $data['transaction'] = Transaction::find($id);
        $data['ledgers'] = Ledger::all();
        return view('accounts.transactions.edit', $data);
    }

    public function update(Request $request, $id)
    {
        Transaction::find($id)->update($request->all());
        return redirect('account/transaction')->with('msg', 'Transaction was Updated Successfully !');
    }

    public function destroy($id)
    {
        Transaction::find($id)->delete();
        return "deleted Succesfully";
    }

    // transaction
    public function transaction(array $request)
    {
        $process = ProcessLedger::where('name', $request["process_name"])->first();
        $mapper = ProcessLedgerMapper::where('process_id', $process->id)->first();

        $creditTranscation = new Transaction();
        $creditTranscation->ledger_id = $mapper->credit_id;
        $creditTranscation->amount = $request["amount"];
        $creditTranscation->description =  $request["description"];
        $creditTranscation->type =  1;
        $creditTranscation->created_by = Auth::user()->id;
        $creditTranscation->save();

        $debitTranscation = new Transaction();
        $debitTranscation->ledger_id = $mapper->credit_id;
        $debitTranscation->amount = $request["amount"];
        $debitTranscation->description =  $request["description"];
        $debitTranscation->type =  2;
        $debitTranscation->created_by =  Auth::user()->id;
        $debitTranscation->save();

        if ($mapper->vat_id) {
            $vatTransaction = new Transaction();
            $vatTransaction->ledger_id = $mapper->credit_id;
            $vatTransaction->amount = $request["amount"];
            $vatTransaction->description =  $request["description"];
            $vatTransaction->type =  2;
            $vatTransaction->created_by = Auth::user()->id;
            $vatTransaction->save();
        }

        return "Transaction was created Succesfully";
    }
}