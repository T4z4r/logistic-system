@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Edit Fuel Cost</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Update fuel cost details</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('fuel-costs.list') }}">Fuel Costs</a>
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
    <!-- Edit Fuel Cost Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Edit Fuel Cost Form</h3>
      </div>
      <div class="block-content">
        @if (isset($fuelCost) && $fuelCost->id)
          <form action="{{ route('fuel-costs.update', $fuelCost->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
              <label class="form-label" for="name">Fuel Cost Name</label>
              <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $fuelCost->name) }}">

            </div>
            <div class="mb-4">
              <label class="form-label" for="ledger_id">Ledger</label>
              <select name="ledger_id" id="ledger_id" class="form-control @error('ledger_id') is-invalid @enderror">
                <option value="">Select User</option>
                @foreach ($users as $user)
                  <option value="{{ $user->id }}" {{ old('ledger_id', $fuelCost->ledger_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
              </select>

            </div>
            <div class="mb-4">
              <label class="form-label" for="created_by">Created By</label>
              <select name="created_by" id="created_by" class="form-control @error('created_by') is-invalid @enderror">
                <option value="">Select User</option>
                @foreach ($users as $user)
                  <option value="{{ $user->id }}" {{ old('created_by', $fuelCost->created_by) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
              </select>

            </div>
            <div class="mb-4">
              <label class="form-label" for="vat">VAT Applicable</label>
              <select name="vat" id="vat" class="form-control @error('vat') is-invalid @enderror">
                <option value="1" {{ old('vat', $fuelCost->vat) == 1 ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('vat', $fuelCost->vat) == 0 ? 'selected' : '' }}>No</option>
              </select>

            </div>
            <div class="mb-4">
              <label class="form-label" for="editable">Editable</label>
              <select name="editable" id="editable" class="form-control @error('editable') is-invalid @enderror">
                <option value="1" {{ old('editable', $fuelCost->editable) == 1 ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('editable', $fuelCost->editable) == 0 ? 'selected' : '' }}>No</option>
              </select>

            </div>
            <button type="submit" class="btn btn-primary">Update Fuel Cost</button>
          </form>
        @else
          <div class="alert alert-danger" role="alert">Fuel cost not found.</div>
        @endif
      </div>
    </div>
    <!-- END Edit Fuel Cost Block -->
  </div>
  <!-- END Page Content -->
@endsection
