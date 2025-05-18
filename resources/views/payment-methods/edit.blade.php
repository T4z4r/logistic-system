@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Edit Payment Method</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Update payment method details</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('payment-methods.list') }}">Payment Methods</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Edit</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content">
    <!-- Edit Payment Method Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Edit Payment Method Form</h3>
      </div>
      <div class="block-content">
        @if (isset($paymentMethod) && $paymentMethod->id)
          <form action="{{ route('payment-methods.update', $paymentMethod->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
              <label class="form-label" for="name">Name</label>
              <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $paymentMethod->name) }}">

            </div>
            <div class="mb-4">
              <label class="form-label" for="ledger_id">Ledger</label>
              <select name="ledger_id" id="ledger_id" class="form-control @error('ledger_id') is-invalid @enderror">
                <option value="">Select Ledger</option>
                @foreach ($ledgers as $ledger)
                  <option value="{{ $ledger->id }}" {{ old('ledger_id', $paymentMethod->ledger_id) == $ledger->id ? 'selected' : '' }}>{{ $ledger->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-4">
              <label class="form-label" for="currency_id">Currency</label>
              <select name="currency_id" id="currency_id" class="form-control @error('currency_id') is-invalid @enderror">
                <option value="0" {{ old('currency_id', $paymentMethod->currency_id) == 0 ? 'selected' : '' }}>None</option>
                @foreach ($currencies as $currency)
                  <option value="{{ $currency->id }}" {{ old('currency_id', $paymentMethod->currency_id) == $currency->id ? 'selected' : '' }}>{{ $currency->code }}</option>
                @endforeach
              </select>

            </div>
            <div class="mb-4">
              <label class="form-label" for="account_number_usd">Account Number (USD)</label>
              <input type="text" name="account_number_usd" id="account_number_usd" class="form-control @error('account_number_usd') is-invalid @enderror" value="{{ old('account_number_usd', $paymentMethod->account_number_usd) }}">

            </div>
            <div class="mb-4">
              <label class="form-label" for="account_number_tzs">Account Number (TZS)</label>
              <input type="text" name="account_number_tzs" id="account_number_tzs" class="form-control @error('account_number_tzs') is-invalid @enderror" value="{{ old('account_number_tzs', $paymentMethod->account_number_tzs) }}">

            </div>
            <div class="mb-4">
              <label class="form-label" for="branch_name">Branch Name</label>
              <input type="text" name="branch_name" id="branch_name" class="form-control @error('branch_name') is-invalid @enderror" value="{{ old('branch_name', $paymentMethod->branch_name) }}">

            </div>
            <div class="mb-4">
              <label class="form-label" for="bank_name">Bank Name</label>
              <input type="text" name="bank_name" id="bank_name" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name', $paymentMethod->bank_name) }}">

            </div>
            <div class="mb-4">
              <label class="form-label" for="swift_code">SWIFT Code</label>
              <input type="text" name="swift_code" id="swift_code" class="form-control @error('swift_code') is-invalid @enderror" value="{{ old('swift_code', $paymentMethod->swift_code) }}">

            </div>
            <div class="mb-4">
              <label class="form-label" for="branch_code">Branch Code</label>
              <input type="text" name="branch_code" id="branch_code" class="form-control @error('branch_code') is-invalid @enderror" value="{{ old('branch_code', $paymentMethod->branch_code) }}">

            </div>
            <div class="mb-4">
              <label class="form-label" for="status">Status</label>
              <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                <option value="1" {{ old('status', $paymentMethod->status) == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('status', $paymentMethod->status) == 0 ? 'selected' : '' }}>Inactive</option>
              </select>

            </div>
            <button type="submit" class="btn btn-primary">Update Payment Method</button>
          </form>
        @else
          <div class="alert alert-danger" role="alert">Payment method not found.</div>
        @endif
      </div>
    </div>
    <!-- END Edit Payment Method Block -->
  </div>
  <!-- END Page Content -->
@endsection
