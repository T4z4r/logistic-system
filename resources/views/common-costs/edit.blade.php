@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Edit Common Cost</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Update common cost details</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('common-costs.list') }}">Common Costs</a>
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
    <!-- Edit Common Cost Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Edit Common Cost Form</h3>
      </div>
      <div class="block-content">
        @if (isset($commonCost) && $commonCost->id)
          <form action="{{ route('common-costs.update', $commonCost->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
              <label class="form-label" for="name">Cost Name</label>
              <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $commonCost->name) }}">
       
            </div>
            <div class="mb-4">
              <label class="form-label" for="ledger_id">Ledger</label>
              <select name="ledger_id" id="ledger_id" class="form-control @error('ledger_id') is-invalid @enderror">
                <option value="">Select User</option>
                @foreach ($users as $user)
                  <option value="{{ $user->id }}" {{ old('ledger_id', $commonCost->ledger_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
              </select>
          
            </div>
            <div class="mb-4">
              <label class="form-label" for="created_by">Created By</label>
              <select name="created_by" id="created_by" class="form-control @error('created_by') is-invalid @enderror">
                <option value="">Select User</option>
                @foreach ($users as $user)
                  <option value="{{ $user->id }}" {{ old('created_by', $commonCost->created_by) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
              </select>
           
            </div>
            <div class="mb-4">
              <label class="form-label" for="vat">VAT Applicable</label>
              <select name="vat" id="vat" class="form-control @error('vat') is-invalid @enderror">
                <option value="1" {{ old('vat', $commonCost->vat) == 1 ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('vat', $commonCost->vat) == 0 ? 'selected' : '' }}>No</option>
              </select>
    
            </div>
            <div class="mb-4">
              <label class="form-label" for="editable">Editable</label>
              <select name="editable" id="editable" class="form-control @error('editable') is-invalid @enderror">
                <option value="1" {{ old('editable', $commonCost->editable) == 1 ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('editable', $commonCost->editable) == 0 ? 'selected' : '' }}>No</option>
              </select>

            </div>
            <div class="mb-4">
              <label class="form-label" for="advancable">Advancable Amount</label>
              <input type="number" name="advancable" id="advancable" class="form-control @error('advancable') is-invalid @enderror" value="{{ old('advancable', $commonCost->advancable) }}">
     
            </div>
            <div class="mb-4">
              <label class="form-label" for="return">Returnable</label>
              <select name="return" id="return" class="form-control @error('return') is-invalid @enderror">
                <option value="1" {{ old('return', $commonCost->return) == 1 ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('return', $commonCost->return) == 0 ? 'selected' : '' }}>No</option>
              </select>
      
            </div>
            <button type="submit" class="btn btn-primary">Update Common Cost</button>
          </form>
        @else
          <div class="alert alert-danger" role="alert">Common cost not found.</div>
        @endif
      </div>
    </div>
    <!-- END Edit Common Cost Block -->
  </div>
  <!-- END Page Content -->
@endsection