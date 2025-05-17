@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Create Common Cost</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Add a new common cost to the system</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('common-costs.list') }}">Common Costs</a>
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
    <!-- Create Common Cost Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">New Common Cost Form</h3>
      </div>
      <div class="block-content">
        <form action="{{ route('common-costs.store') }}" method="POST">
          @csrf
          <div class="mb-4">
            <label class="form-label" for="name">Cost Name</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="ledger_id">Ledger</label>
            <select name="ledger_id" id="ledger_id" class="form-control @error('ledger_id') is-invalid @enderror">
              <option value="">Select User</option>
              @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ old('ledger_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
              @endforeach
            </select>
            @error('ledger_id')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
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
          </div>
          <div class="mb-4">
            <label class="form-label" for="vat">VAT Applicable</label>
            <select name="vat" id="vat" class="form-control @error('vat') is-invalid @enderror">
              <option value="1" {{ old('vat', 0) == 1 ? 'selected' : '' }}>Yes</option>
              <option value="0" {{ old('vat', 0) == 0 ? 'selected' : '' }}>No</option>
            </select>
            @error('vat')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="editable">Editable</label>
            <select name="editable" id="editable" class="form-control @error('editable') is-invalid @enderror">
              <option value="1" {{ old('editable', 0) == 1 ? 'selected' : '' }}>Yes</option>
              <option value="0" {{ old('editable', 0) == 0 ? 'selected' : '' }}>No</option>
            </select>
            @error('editable')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="advancable">Advancable Amount</label>
            <input type="number" name="advancable" id="advancable" class="form-control @error('advancable') is-invalid @enderror" value="{{ old('advancable', 0) }}">
            @error('advancable')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="return">Returnable</label>
            <select name="return" id="return" class="form-control @error('return') is-invalid @enderror">
              <option value="1" {{ old('return', 0) == 1 ? 'selected' : '' }}>Yes</option>
              <option value="0" {{ old('return', 0) == 0 ? 'selected' : '' }}>No</option>
            </select>
            @error('return')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <button type="submit" class="btn btn-primary">Create Common Cost</button>
        </form>
      </div>
    </div>
    <!-- END Create Common Cost Block -->
  </div>
  <!-- END Page Content -->
@endsection