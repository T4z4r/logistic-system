@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Edit Allocation</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Update allocation details</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('allocations.list') }}">Allocations</a>
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
    <!-- Edit Allocation Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Edit Allocation Form</h3>
      </div>
      <div class="block-content">
        @if (isset($allocation) && $allocation->id)
          @if ($errors->any())
            <div class="alert alert-danger" role="alert">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <form action="{{ route('allocations.update', $allocation->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
              <div class="col-md-6">
                <div class="mb-4">
                  <label class="form-label" for="ref_no">Reference Number</label>
                  <input type="text" name="ref_no" id="ref_no" class="form-control @error('ref_no') is-invalid @enderror" value="{{ old('ref_no', $allocation->ref_no) }}">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="Customer_id">Customer</label>
                  <select name="Customer_id" id="Customer_id" class="form-control @error('Customer_id') is-invalid @enderror">
                    <option value="">Select Customer</option>
                    @foreach ($customers as $customer)
                      <option value="{{ $customer->id }}" {{ old('Customer_id', $allocation->Customer_id) == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                    @endforeach
                  </select>

                </div>
                <div class="mb-4">
                  <label class="form-label" for="amount">Amount</label>
                  <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $allocation->amount) }}">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="quantity">Quantity</label>
                  <input type="number" step="0.01" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $allocation->quantity) }}">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="cargo">Cargo</label>
                  <input type="text" name="cargo" id="cargo" class="form-control @error('cargo') is-invalid @enderror" value="{{ old('cargo', $allocation->cargo) }}">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="cargo_ref">Cargo Reference</label>
                  <input type="text" name="cargo_ref" id="cargo_ref" class="form-control @error('cargo_ref') is-invalid @enderror" value="{{ old('cargo_ref', $allocation->cargo_ref) }}">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="estimated_pay">Estimated Pay</label>
                  <input type="number" step="0.01" name="estimated_pay" id="estimated_pay" class="form-control @error('estimated_pay') is-invalid @enderror" value="{{ old('estimated_pay', $allocation->estimated_pay) }}">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="cargo_nature_id">Cargo Nature</label>
                  <select name="cargo_nature_id" id="cargo_nature_id" class="form-control @error('cargo_nature_id') is-invalid @enderror">
                    <option value="">Select Cargo Nature</option>
                    @foreach ($cargoNatures as $cargoNature)
                      <option value="{{ $cargoNature->id }}" {{ old('cargo_nature_id', $allocation->cargo_nature_id) == $cargoNature->id ? 'selected' : '' }}>{{ $cargoNature->name }}</option>
                    @endforeach
                  </select>

                </div>
                <div class="mb-4">
                  <label class="form-label" for="payment_mode_id">Payment Mode</label>
                  <select name="payment_mode_id" id="payment_mode_id" class="form-control @error('payment_mode_id') is-invalid @enderror">
                    <option value="">Select Payment Mode</option>
                    @foreach ($paymentModes as $paymentMode)
                      <option value="{{ $paymentMode->id }}" {{ old('payment_mode_id', $allocation->payment_mode_id) == $paymentMode->id ? 'selected' : '' }}>{{ $paymentMode->name }}</option>
                    @endforeach
                  </select>

                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-4">
                  <label class="form-label" for="loading_site">Loading Site</label>
                  <input type="text" name="loading_site" id="loading_site" class="form-control @error('loading_site') is-invalid @enderror" value="{{ old('loading_site', $allocation->loading_site) }}">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="offloading_site">Offloading Site</label>
                  <input type="text" name="offloading_site" id="offloading_site" class="form-control @error('offloading_site') is-invalid @enderror" value="{{ old('offloading_site', $allocation->offloading_site) }}">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="clearance">Clearance</label>
                  <select name="clearance" id="clearance" class="form-control @error('clearance') is-invalid @enderror">
                    <option value="No" {{ old('clearance', $allocation->clearance) == 'No' ? 'selected' : '' }}>No</option>
                    <option value="Yes" {{ old('clearance', $allocation->clearance) == 'Yes' ? 'selected' : '' }}>Yes</option>
                  </select>

                </div>
                <div class="mb-4">
                  <label class="form-label" for="container">Container</label>
                  <select name="container" id="container" class="form-control @error('container') is-invalid @enderror">
                    <option value="No" {{ old('container', $allocation->container) == 'No' ? 'selected' : '' }}>No</option>
                    <option value="Yes" {{ old('container', $allocation->container) == 'Yes' ? 'selected' : '' }}>Yes</option>
                  </select>

                </div>
                <div class="mb-4">
                  <label class="form-label" for="container_type">Container Type</label>
                  <input type="text" name="container_type" id="container_type" class="form-control @error('container_type') is-invalid @enderror" value="{{ old('container_type', $allocation->container_type) }}">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="dimensions">Dimensions</label>
                  <input type="text" name="dimensions" id="dimensions" class="form-control @error('dimensions') is-invalid @enderror" value="{{ old('dimensions', $allocation->dimensions) }}">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="payment_currency">Payment Currency</label>
                  <input type="text" name="payment_currency" id="payment_currency" class="form-control @error('payment_currency') is-invalid @enderror" value="{{ old('payment_currency', $allocation->payment_currency) }}">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="rate">Rate</label>
                  <input type="number" step="0.01" name="rate" id="rate" class="form-control @error('rate') is-invalid @enderror" value="{{ old('rate', $allocation->rate) }}">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="real_amount">Real Amount</label>
                  <input type="number" step="0.01" name="real_amount" id="real_amount" class="form-control @error('real_amount') is-invalid @enderror" value="{{ old('real_amount', $allocation->real_amount) }}">

                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="mb-4">
                  <label class="form-label" for="route_id">Route</label>
                  <select name="route_id" id="route_id" class="form-control @error('route_id') is-invalid @enderror">
                    <option value="">Select Route</option>
                    @foreach ($routes as $route)
                      <option value="{{ $route->id }}" {{ old('route_id', $allocation->route_id) == $route->id ? 'selected' : '' }}>{{ $route->name }}</option>
                    @endforeach
                  </select>

                </div>
                <div class="mb-4">
                  <label class="form-label" for="start_date">Start Date</label>
                  <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $allocation->start_date->format('Y-m-d')) }}">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="end_date">End Date</label>
                  <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $allocation->end_date->format('Y-m-d')) }}">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="unit">Unit</label>
                  <input type="text" name="unit" id="unit" class="form-control @error('unit') is-invalid @enderror" value="{{ old('unit', $allocation->unit) }}">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="status">Status</label>
                  <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                    <option value="1" {{ old('status', $allocation->status) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $allocation->status) == 0 ? 'selected' : '' }}>Inactive</option>
                  </select>

                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-4">
                  <label class="form-label" for="approval_status">Approval Status</label>
                  <select name="approval_status" id="approval_status" class="form-control @error('approval_status') is-invalid @enderror">
                    <option value="0" {{ old('approval_status', $allocation->approval_status) == 0 ? 'selected' : '' }}>Pending</option>
                    <option value="1" {{ old('approval_status', $allocation->approval_status) == 1 ? 'selected' : '' }}>Approved</option>
                    <option value="2" {{ old('approval_status', $allocation->approval_status) == 2 ? 'selected' : '' }}>Disapproved</option>
                  </select>

                </div>
                <div class="mb-4">
                  <label class="form-label" for="type">Type</label>
                  <select name="type" id="type" class="form-control @error('type') is-invalid @enderror">
                    <option value="1" {{ old('type', $allocation->type) == 1 ? 'selected' : '' }}>Type 1</option>
                    <option value="2" {{ old('type', $allocation->type) == 2 ? 'selected' : '' }}>Type 2</option>
                  </select>

                </div>
                <div class="mb-4">
                  <label class="form-label" for="state">State</label>
                  <input type="text" name="state" id="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state', $allocation->state) }}">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="goingload_id">Going Load ID</label>
                  <input type="number" name="goingload_id" id="goingload_id" class="form-control @error('goingload_id') is-invalid @enderror" value="{{ old('goingload_id', $allocation->goingload_id) }}">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="approver_id">Approver ID</label>
                  <input type="number" name="approver_id" id="approver_id" class="form-control @error('approver_id') is-invalid @enderror" value="{{ old('approver_id', $allocation->approver_id) }}">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="disapprover_id">Disapprover ID</label>
                  <input type="number" name="disapprover_id" id="disapprover_id" class="form-control @error('disapprover_id') is-invalid @enderror" value="{{ old('disapprover_id', $allocation->disapprover_id) }}">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="usd_income">USD Income</label>
                  <input type="number" step="0.01" name="usd_income" id="usd_income" class="form-control @error('usd_income') is-invalid @enderror" value="{{ old('usd_income', $allocation->usd_income) }}">

                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary">Update Allocation</button>
          </form>
        @else
          <div class="alert alert-danger" role="alert">Allocation not found.</div>
        @endif
      </div>
    </div>
    <!-- END Edit Allocation Block -->
  </div>
  <!-- END Page Content -->
@endsection
