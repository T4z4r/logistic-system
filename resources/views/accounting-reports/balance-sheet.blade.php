@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
        <div class="flex-grow-1 text-center text-sm-start">
          <h5 class="h5 text-main fw-bold mb-1">Balance Sheet</h5>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Assets and liabilities summary</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Balance Sheet</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content1 p-2 rounded-0">
    <div class="block block-rounded shadow-sm py-5 rounded-0">
      <form method="GET" action="{{ route('reports.balance-sheet') }}" class="mb-4">
        <div class="row">
          <div class="col-md-4">
            <label for="end_date" class="form-label">As of Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
          </div>
          <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-alt-primary">Filter</button>
          </div>
        </div>
      </form>
      <div class="row">
        <div class="col-md-6">
          <h5>Assets</h5>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Ledger</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($assets as $asset)
                <tr>
                  <td>{{ $asset['ledger'] }}</td>
                  <td>{{ number_format($asset['balance'], 2) }}</td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th>Total Assets</th>
                <th>{{ number_format($assets->sum('balance'), 2) }}</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <div class="col-md-6">
          <h5>Liabilities & Capital</h5>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Ledger</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($liabilities as $liability)
                <tr>
                  <td>{{ $liability['ledger'] }}</td>
                  <td>{{ number_format($liability['balance'], 2) }}</td>
                </tr>
              @endforeach
              <tr>
                <td>Net Profit/Loss</td>
                <td>{{ number_format($netProfit, 2) }}</td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <th>Total Liabilities & Capital</th>
                <th>{{ number_format($liabilities->sum('balance') + $netProfit, 2) }}</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <div class="text-end mt-3">
        <button class="btn btn-alt-primary" onclick="alert('Export to PDF functionality to be implemented.')">Export to PDF</button>
        <button class="btn btn-alt-primary ms-2" onclick="alert('Export to Excel functionality to be implemented.')">Export to Excel</button>
      </div>
    </div>
  </div>
  <!-- END Page Content -->
@endsection

