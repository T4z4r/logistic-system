@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Create Trailer</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Add a new trailer to the system</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('trailers.list') }}">Trailers</a>
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
    <!-- Create Trailer Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">New Trailer Form</h3>
      </div>
      <div class="block-content">
        <form action="{{ route('trailers.store') }}" method="POST">
          @csrf
          <div class="mb-4">
            <label class="form-label" for="plate_number">Plate Number</label>
            <input type="text" name="plate_number" id="plate_number" class="form-control @error('plate_number') is-invalid @enderror" value="{{ old('plate_number') }}">
            @error('plate_number')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="purchase_date">Purchase Date</label>
            <input type="date" name="purchase_date" id="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" value="{{ old('purchase_date') }}">
            @error('purchase_date')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="amount">Amount</label>
            <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}">
            @error('amount')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="capacity">Capacity</label>
            <input type="number" step="0.01" name="capacity" id="capacity" class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity') }}">
            @error('capacity')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="manufacturer">Manufacturer</label>
            <input type="text" name="manufacturer" id="manufacturer" class="form-control @error('manufacturer') is-invalid @enderror" value="{{ old('manufacturer') }}">
            @error('manufacturer')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="length">Length</label>
            <input type="number" step="0.01" name="length" id="length" class="form-control @error('length') is-invalid @enderror" value="{{ old('length') }}">
            @error('length')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="trailer_type">Trailer Type</label>
            <input type="text" name="trailer_type" id="trailer_type" class="form-control @error('trailer_type') is-invalid @enderror" value="{{ old('trailer_type') }}">
            @error('trailer_type')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="status">Status</label>
            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
              <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
              <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="added_by">Added By</label>
            <select name="added_by" id="added_by" class="form-control @error('added_by') is-invalid @enderror">
              <option value="">Select User</option>
              @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ old('added_by') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
              @endforeach
            </select>
            @error('added_by')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <button type="submit" class="btn btn-primary">Create Trailer</button>
        </form>
      </div>
    </div>
    <!-- END Create Trailer Block -->
  </div>
  <!-- END Page Content -->
@endsection
