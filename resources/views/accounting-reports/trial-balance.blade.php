@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
        <div class="flex-grow-1 text-center text-sm-start">
          <h5 class="h5 text-main fw-bold mb-1">Trial Balance</h5>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Summary of ledger balances</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Trial Balance</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content1 p-2 rounded-0">
    <div class="block block-rounded shadow-sm py-5 px-2 rounded-0">
      <form method="GET" action="{{ route('reports.trial-balance') }}" class="mb-4">
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
      <div class="table-responsive">
        <table class="table table-bordered table-sm">
          <thead class="table-secondary">
            <tr>
              <th>Ledger</th>
              <th>Opening Balance</th>
              <th>Debit</th>
              <th>Credit</th>
              <th>Closing Balance</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($trialBalance as $item)
              <tr>
                <td>{{ $item['ledger'] }}</td>
                <td>{{ number_format($item['opening_balance'], 2) }}</td>
                <td>{{ number_format($item['debit'], 2) }}</td>
                <td>{{ number_format($item['credit'], 2) }}</td>
                <td>{{ number_format($item['closing_balance'], 2) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center">No data available for the selected period.</td>
              </tr>
            @endforelse
          </tbody>
          @if ($trialBalance->count())
            <tfoot>
              <tr>
                <th>Total</th>
                <th>{{ number_format($trialBalance->sum('opening_balance'), 2) }}</th>
                <th>{{ number_format($trialBalance->sum('debit'), 2) }}</th>
                <th>{{ number_format($trialBalance->sum('credit'), 2) }}</th>
                <th>{{ number_format($trialBalance->sum('closing_balance'), 2) }}</th>
              </tr>
            </tfoot>
          @endif
        </table>
      </div>
      <div class="text-end mt-3">
        <button class="btn btn-alt-primary" onclick="alert('Export to PDF functionality to be implemented.')">Export to PDF</button>
        <button class="btn btn-alt-primary ms-2" onclick="alert('Export to Excel functionality to be implemented.')">Export to Excel</button>
      </div>
    </div>
  </div>
  <!-- END Page Content -->
@endsection

