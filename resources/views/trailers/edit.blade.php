@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Edit Trailer</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Update trailer details</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('trailers.list') }}">Trailers</a>
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
    <!-- Edit Trailer Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Edit Trailer Form</h3>
      </div>
      <div class="block-content">
        @if (isset($trailer) && $trailer->id)
          <form action="{{ route('trailers.update', $trailer->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
              <label class="form-label" for="plate_number">Plate Number</label>
              <input type="text" name="plate_number" id="plate_number" class="form-control @error('plate_number') is-invalid @enderror" value="{{ old('plate_number', $trailer->plate_number) }}">

            </div>
            <div class="mb-4">
              <label class="form-label" for="purchase_date">Purchase Date</label>
              <input type="date" name="purchase_date" id="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" value="{{ old('purchase_date', $trailer->purchase_date) }}">

            </div>
            <div class="mb-4">
              <label class="form-label" for="amount">Amount</label>
              <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $trailer->amount) }}">

            </div>
            <div class="mb-4">
              <label class="form-label" for="capacity">Capacity</label>
              <input type="number" step="0.01" name="capacity" id="capacity" class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity', $trailer->capacity) }}">

            </div>
            <div class="mb-4">
              <label class="form-label" for="manufacturer">Manufacturer</label>
              <input type="text" name="manufacturer" id="manufacturer" class="form-control @error('manufacturer') is-invalid @enderror" value="{{ old('manufacturer', $trailer->manufacturer) }}">

            </div>
            <div class="mb-4">
              <label class="form-label" for="length">Length</label>
              <input type="number" step="0.01" name="length" id="length" class="form-control @error('length') is-invalid @enderror" value="{{ old('length', $trailer->length) }}">

            </div>
            <div class="mb-4">
              <label class="form-label" for="trailer_type">Trailer Type</label>
              <input type="text" name="trailer_type" id="trailer_type" class="form-control @error('trailer_type') is-invalid @enderror" value="{{ old('trailer_type', $trailer->trailer_type) }}">

            </div>
            <div class="mb-4">
              <label class="form-label" for="status">Status</label>
              <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                <option value="1" {{ old('status', $trailer->status) == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('status', $trailer->status) == 0 ? 'selected' : '' }}>Inactive</option>
              </select>

            </div>
            <div class="mb-4">
              <label class="form-label" for="added_by">Added By</label>
              <select name="added_by" id="added_by" class="form-control @error('added_by') is-invalid @enderror">
                <option value="">Select User</option>
                @foreach ($users as $user)
                  <option value="{{ $user->id }}" {{ old('added_by', $trailer->added_by) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
              </select>

            </div>
            <button type="submit" class="btn btn-primary">Update Trailer</button>
          </form>
        @else
          <div class="alert alert-danger" role="alert">Trailer not found.</div>
        @endif
      </div>
    </div>
    <!-- END Edit Trailer Block -->
  </div>
  <!-- END Page Content -->
@endsection
