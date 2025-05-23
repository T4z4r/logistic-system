@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light mt-5">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h5 class="h5 fw-bold mb-1">Create Customer</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Add a new customer to the system</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('customers.index') }}">Customers</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Create</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content p-2">
    <!-- Create Customer Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">New Customer Form</h3>
      </div>
      <div class="block-content">
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf
            <div class="row">
            <div class="col-md-6 mb-4">
                <label class="form-label" for="company">Company</label>
                <input type="text" name="company" id="company" class="form-control @error('company') is-invalid @enderror" value="{{ old('company') }}" placeholder="Enter company name">
                @error('company')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-4">
                <label class="form-label" for="contact_person">Contact Person</label>
                <input type="text" name="contact_person" id="contact_person" class="form-control @error('contact_person') is-invalid @enderror" value="{{ old('contact_person') }}" placeholder="Enter contact person's name">
                @error('contact_person')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-4">
                <label class="form-label" for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Enter email address">
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-4">
                <label class="form-label" for="phone">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="Enter phone number">
                @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-4">
                <label class="form-label" for="TIN">TIN</label>
                <input type="text" name="TIN" id="TIN" class="form-control @error('TIN') is-invalid @enderror" value="{{ old('TIN') }}" placeholder="Enter TIN">
                @error('TIN')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-4">
                <label class="form-label" for="VRN">VRN</label>
                <input type="text" name="VRN" id="VRN" class="form-control @error('VRN') is-invalid @enderror" value="{{ old('VRN') }}" placeholder="Enter VRN">
                @error('VRN')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-4">
                <label class="form-label" for="address">Address</label>
                <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}" placeholder="Enter address">
                @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-4">
                <label class="form-label" for="abbreviation">Abbreviation</label>
                <input type="text" name="abbreviation" id="abbreviation" class="form-control @error('abbreviation') is-invalid @enderror" value="{{ old('abbreviation') }}" placeholder="Enter abbreviation">
                @error('abbreviation')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- <div class="col-md-6 mb-4">
                <label class="form-label" for="created_by">Created By</label>
                <select name="created_by" id="created_by" class="form-control @error('created_by') is-invalid @enderror">
                <option value="">Select User</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('created_by') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
                </select>
                @error('created_by')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div> --}}

            <div class="col-md-6 mb-4">
                <label class="form-label" for="status">Status</label>
                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-4">
                <label class="form-label" for="credit_term">Credit Term (days)</label>
                <input type="number" name="credit_term" id="credit_term" class="form-control @error('credit_term') is-invalid @enderror" value="{{ old('credit_term') }}" placeholder="Enter credit term in days">
                @error('credit_term')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">

                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary float-end mb-3">
                        <i class="fa fa-save"></i>
                        Create Customer
                    </button>
                </div>
            </div>
        </form>
      </div>
    </div>
    <!-- END Create Customer Block -->
  </div>
  <!-- END Page Content -->
@endsection
 
