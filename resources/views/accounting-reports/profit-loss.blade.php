@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
        <div class="flex-grow-1 text-center text-sm-start">
          <h5 class="h5 text-main fw-bold mb-1">Profit & Loss</h5>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Income and expense summary</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Profit & Loss</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content1 p-2 rounded-0">
    <div class="block block-rounded shadow-sm py-5 px-2 rounded-0">
      <form method="GET" action="{{ route('reports.profit-loss') }}" class="mb-4">
        <div class="row">
          <div class="col-md-4">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
          </div>
          <div class="col-md-4">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
          </div>
          <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-alt-primary">Filter</button>
          </div>
        </div>
      </form>
      <hr>
      <div class="row">
        <div class="col-md-6">
          <h5>Income</h5>
          <table class="table table-bordered table-sm">
            <thead class="table-secondary">
              <tr>
                <th>Ledger</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($incomeLedgers as $ledger)
                @php
                  $amount = $ledger->entries->where('type', 'credit')->sum('amount') - $ledger->entries->where('type', 'debit')->sum('amount');
                @endphp
                @if ($amount != 0)
                  <tr>
                    <td>{{ $ledger->name }}</td>
                    <td>{{ number_format($amount, 2) }}</td>
                  </tr>
                @endif
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th>Total Income</th>
                <th>{{ number_format($income, 2) }}</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <div class="col-md-6">
          <h5>Expenses</h5>
          <table class="table table-bordered table-sm">
            <thead class="table-secondary">
              <tr>
                <th>Ledger</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($expenseLedgers as $ledger)
                @php
                  $amount = $ledger->entries->where('type', 'debit')->sum('amount') - $ledger->entries->where('type', 'credit')->sum('amount');
                @endphp
                @if ($amount != 0)
                  <tr>
                    <td>{{ $ledger->name }}</td>
                    <td>{{ number_format($amount, 2) }}</td>
                  </tr>
                @endif
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th>Total Expenses</th>
                <th>{{ number_format($expense, 2) }}</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <div class="mt-4 text-center">
        <h5>Net Profit/Loss: {{ number_format($netProfit, 2) }}</h5>
      </div>
      <div class="text-end mt-3">
        <button class="btn btn-alt-primary" onclick="alert('Export to PDF functionality to be implemented.')">Export to PDF</button>
        <button class="btn btn-alt-primary ms-2" onclick="alert('Export to Excel functionality to be implemented.')">Export to Excel</button>
      </div>
    </div>
  </div>
  <!-- END Page Content -->
@endsection
