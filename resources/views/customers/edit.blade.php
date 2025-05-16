
@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Edit Customer</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Update customer details</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link fall back to a previous version of the document or software-fx" href="{{ route('customers.index') }}">Customers</a>
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
    <!-- Edit Customer Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Edit Customer Form</h3>
      </div>
      <div class="block-content">
        @if (isset($customer) && $customer->id)
          <form action="{{ route('customers.update', $customer->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
              <label class="form-label" for="company">Company</label>
              <input type="text" name="company" id="company" class="form-control @error('company') is-invalid @enderror" value="{{ old('company', $customer->company) }}">

            </div>

            <div class="mb-4">
              <label class="form-label" for="contact_person">Contact Person</label>
              <input type="text" name="contact_person" id="contact_person" class="form-control @error('contact_person') is-invalid @enderror" value="{{ old('contact_person', $customer->contact_person) }}">


            </div>

            <div class="mb-4">
              <label class="form-label" for="email">Email</label>
              <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $customer->email) }}">

            </div>

            <div class="mb-4">
              <label class="form-label" for="phone">Phone</label>
              <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $customer->phone) }}">

            </div>

            <div class="mb-4">
              <label class="form-label" for="TIN">TIN</label>
              <input type="text" name="TIN" id="TIN" class="form-control @error('TIN') is-invalid @enderror" value="{{ old('TIN', $customer->TIN) }}">

            </div>

            <div class="mb-4">
              <label class="form-label" for="VRN">VRN</label>
              <input type="text" name="VRN" id="VRN" class="form-control @error('VRN') is-invalid @enderror" value="{{ old('VRN', $customer->VRN) }}">

            </div>

            <div class="mb-4">
              <label class="form-label" for="address">Address</label>
              <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $customer->address) }}">

            </div>

            <div class="mb-4">
              <label class="form-label" for="abbreviation">Abbreviation</label>
              <input type="text" name="abbreviation" id="abbreviation" class="form-control @error('abbreviation') is-invalid @enderror" value="{{ old('abbreviation', $customer->abbreviation) }}">

            </div>

            <div class="mb-4">
              <label class="form-label" for="created_by">Created By</label>
              <select name="created_by" id="created_by" class="form-control @error('created_by') is-invalid @enderror">
                <option value="">Select User</option>
                @foreach ($users as $user)
                  <option value="{{ $user->id }}" {{ old('created_by', $customer->created_by) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
              </select>

            </div>

            <div class="mb-4">
              <label class="form-label" for="status">Status</label>
              <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                <option value="1" {{ old('status', $customer->status) == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('status', $customer->status) == 0 ? 'selected' : '' }}>Inactive</option>
              </select>

            </div>

            <div class="mb-4">
              <label class="form-label" for="credit_term">Credit Term (days)</label>
              <input type="number" name="credit_term" id="credit_term" class="form-control @error('credit_term') is-invalid @enderror" value="{{ old('credit_term', $customer->credit_term) }}">

            </div>

            <button type="submit" class="btn btn-primary">Update Customer</button>
          </form>
        @else
          <div class="alert alert-danger" role="alert">Customer not found.</div>
        @endif
      </div>
    </div>
    <!-- END Edit Customer Block -->
  </div>
  <!-- END Page Content -->
@endsection
