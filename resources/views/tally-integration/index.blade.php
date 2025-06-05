@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
        <div class="flex-grow-1 text-center text-sm-start">
          <h5 class="h5 text-main fw-bold mb-1">Tally Integration</h5>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Sync data with Tally ERP 9/Prime</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Tally Integration</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content1 p-2 rounded-0">
    <div class="block block-rounded shadow-sm py-5 px-2 rounded-0">
      @if (session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
      @endif
      @if (session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
      @endif
      <div class="mb-4">
        <h5>Connection Status</h5>
        <p class="{{ $connectionStatus ? 'text-success' : 'text-danger' }}">
          Tally Server: {{ $connectionStatus ? 'Connected' : 'Not Connected' }}
        </p>
      </div>
      <hr>
      <div class="row">
        <div class="col-md-6">
          <h5>Ledger Integration</h5>
          <form action="{{ route('tally.import-ledgers') }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-alt-primary" {{ $connectionStatus ? '' : 'disabled' }}>
              <i class="fa fa-download me-1"></i> Import Ledgers from Tally
            </button>
          </form>
          <form action="{{ route('tally.export-ledgers') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-alt-primary" {{ $connectionStatus ? '' : 'disabled' }}>
              <i class="fa fa-upload me-1"></i> Export Ledgers to Tally
            </button>
          </form>
        </div>
        <div class="col-md-6">
          <h5>Voucher Integration</h5>
          <form action="{{ route('tally.import-vouchers') }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-alt-primary" {{ $connectionStatus ? '' : 'disabled' }}>
              <i class="fa fa-download me-1"></i> Import Vouchers from Tally
            </button>
          </form>
          <form action="{{ route('tally.export-vouchers') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-alt-primary" {{ $connectionStatus ? '' : 'disabled' }}>
              <i class="fa fa-upload me-1"></i> Export Vouchers to Tally
            </button>
          </form>
        </div>
      </div>
      <hr>
      <div class="row mt-4">
        <div class="col-md-6">
          <h5>Stock Item Integration</h5>
          <form action="{{ route('tally.import-stock-items') }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-alt-primary" {{ $connectionStatus ? '' : 'disabled' }}>
              <i class="fa fa-download me-1"></i> Import Stock Items from Tally
            </button>
          </form>
          <form action="{{ route('tally.export-stock-items') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-alt-primary" {{ $connectionStatus ? '' : 'disabled' }}>
              <i class="fa fa-upload me-1"></i> Export Stock Items to Tally
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- END Page Content -->
@endsection

