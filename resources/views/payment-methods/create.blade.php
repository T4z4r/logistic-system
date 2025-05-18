@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Create Payment Method</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Add a new payment method</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('payment-methods.list') }}">Payment Methods</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Create</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content">
    <!-- Create Payment Method Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">New Payment Method Form</h3>
      </div>
      <div class="block-content">
        <form action="{{ route('payment-methods.store') }}" method="POST">
          @csrf
          <div class="mb-4">
            <label class="form-label" for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="ledger_id">Ledger</label>
            <select name="ledger_id" id="ledger_id" class="form-control @error('ledger_id') is-invalid @enderror">
              <option value="">Select Ledger</option>
              @foreach ($ledgers as $ledger)
                <option value="{{ $ledger->id }}" {{ old('ledger_id') == $ledger->id ? 'selected' : '' }}>{{ $ledger->name }}</option>
              @endforeach
            </select>
            @error('ledger_id')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="currency_id">Currency</label>
            <select name="currency_id" id="currency_id" class="form-control @error('currency_id') is-invalid @enderror">
              <option value="0" {{ old('currency_id', 0) == 0 ? 'selected' : '' }}>None</option>
              @foreach ($currencies as $currency)
                <option value="{{ $currency->id }}" {{ old('currency_id') == $currency->id ? 'selected' : '' }}>{{ $currency->code }}</option>
              @endforeach
            </select>
            @error('currency_id')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="account_number_usd">Account Number (USD)</label>
            <input type="text" name="account_number_usd" id="account_number_usd" class="form-control @error('account_number_usd') is-invalid @enderror" value="{{ old('account_number_usd') }}">
            @error('account_number_usd')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="account_number_tzs">Account Number (TZS)</label>
            <input type="text" name="account_number_tzs" id="account_number_tzs" class="form-control @error('account_number_tzs') is-invalid @enderror" value="{{ old('account_number_tzs') }}">
            @error('account_number_tzs')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="branch_name">Branch Name</label>
            <input type="text" name="branch_name" id="branch_name" class="form-control @error('branch_name') is-invalid @enderror" value="{{ old('branch_name') }}">
            @error('branch_name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          SES
          <div class="mb-4">
            <label class="form-label" for="bank_name">Bank Name</label>
            <input type="text" name="bank_name" id="bank_name" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name') }}">
            @error('bank_name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="swift_code">SWIFT Code</label>
            <input type="text" name="swift_code" id="swift_code" class="form-control @error('swift_code') is-invalid @enderror" value="{{ old('swift_code') }}">
            @error('swift_code')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="branch_code">Branch Code</label>
            <input type="text" name="branch_code" id="branch_code" class="form-control @error('branch_code') is-invalid @enderror" value="{{ old('branch_code') }}">
            @error('branch_code')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="status">Status</label>
            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
              <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
              <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <button type="submit" class="btn btn-primary">Create Payment Method</button>
        </form>
      </div>
    </div>
    <!-- END Create Payment Method Block -->
  </div>
  <!-- END Page Content -->
@endsection
