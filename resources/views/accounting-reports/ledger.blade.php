@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
        <div class="flex-grow-1 text-center text-sm-start">
          <h5 class="h5 text-main fw-bold mb-1">Ledger Report</h5>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Detailed ledger transactions</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Ledger</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content1 p-2 rounded-0">
    <div class="block block-rounded shadow-sm py-5 px-2 rounded-0">
      <form method="GET" action="{{ route('reports.ledger') }}" class="mb-4">
        <div class="row">
          <div class="col-md-4">
            <label for="ledger_id" class="form-label">Ledger</label>
            <select class="form-control" id="ledger_id" name="ledger_id">
              <option value="">Select Ledger</option>
              @foreach ($ledgers as $ledger)
                <option value="{{ $ledger->id }}" {{ $selectedLedgerId == $ledger->id ? 'selected' : '' }}>{{ $ledger->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
          </div>
          <div class="col-md-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
          </div>
          <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-alt-primary">Filter</button>
          </div>
        </div>
      </form>
      <hr>
      @if ($ledger)
        <h5>Ledger: {{ $ledger->name }}</h5>
        <div class="table-responsive">
          <table class="table table-bordered table-sm">
            <thead class="table-secondary">
              <tr>
                <th>Date</th>
                <th>Voucher Number</th>
                <th>Narration</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="3">Opening Balance</td>
                <td></td>
                <td></td>
                <td>{{ number_format($ledger->opening_balance, 2) }}</td>
              </tr>
              @foreach ($transactions as $transaction)
                <tr>
                  <td>{{ $transaction['date']->format('Y-m-d') }}</td>
                  <td>{{ $transaction['voucher_number'] }}</td>
                  <td>{{ $transaction['narration'] ?? 'N/A' }}</td>
                  <td>{{ number_format($transaction['debit'], 2) }}</td>
                  <td>{{ number_format($transaction['credit'], 2) }}</td>
                  <td>{{ number_format($transaction['balance'], 2) }}</td>
                </tr>
              @endforeach
            </tbody>
            @if ($transactions->count())
              <tfoot>
                <tr>
                  <th colspan="3">Total</th>
                  <th>{{ number_format($transactions->sum('debit'), 2) }}</th>
                  <th>{{ number_format($transactions->sum('credit'), 2) }}</th>
                  <th>{{ number_format($transactions->last()['balance'], 2) }}</th>
                </tr>
              </tfoot>
            @endif
          </table>
        </div>
      @else
        <p class="text-center">Please select a ledger to view transactions.</p>
      @endif
      @if ($ledger)
        <div class="text-end mt-3">
          <button class="btn btn-alt-primary" onclick="alert('Export to PDF functionality to be implemented.')">Export to PDF</button>
          <button class="btn btn-alt-primary ms-2" onclick="alert('Export to Excel functionality to be implemented.')">Export to Excel</button>
        </div>
      @endif
    </div>
  </div>
  <!-- END Page Content -->
@endsection

