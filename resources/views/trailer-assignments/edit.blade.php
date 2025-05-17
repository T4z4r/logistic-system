@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Edit Trailer Assignment</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Update trailer assignment details</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('trailer-assignments.list') }}">Trailer Assignments</a>
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
    <!-- Edit Assignment Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Edit Trailer Assignment Form</h3>
      </div>
      <div class="block-content">
        @if (isset($assignment) && $assignment->id)
          <form action="{{ route('trailer-assignments.update', $assignment->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
              <label class="form-label" for="trailer_id">Trailer</label>
              <select name="trailer_id" id="trailer_id" class="form-control @error('trailer_id') is-invalid @enderror">
                <option value="">Select Trailer</option>
                @foreach ($trailers as $trailer)
                  <option value="{{ $trailer->id }}" {{ old('trailer_id', $assignment->trailer_id) == $trailer->id ? 'selected' : '' }}>{{ $trailer->plate_number }}</option>
                @endforeach
              </select>
        
            </div>
            <div class="mb-4">
              <label class="form-label" for="truck_id">Truck</label>
              <select name="truck_id" id="truck_id" class="form-control @error('truck_id') is-invalid @enderror">
                <option value="">Select Truck</option>
                @foreach ($trucks as $truck)
                  <option value="{{ $truck->id }}" {{ old('truck_id', $assignment->truck_id) == $truck->id ? 'selected' : '' }}>{{ $truck->plate_number }}</option>
                @endforeach
              </select>
          
            </div>
            <div class="mb-4">
              <label class="form-label" for="status">Status</label>
              <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                <option value="1" {{ old('status', $assignment->status) == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('status', $assignment->status) == 0 ? 'selected' : '' }}>Inactive</option>
              </select>
          
            </div>
            <button type="submit" class="btn btn-primary">Update Assignment</button>
          </form>
        @else
          <div class="alert alert-danger" role="alert">Trailer assignment not found.</div>
        @endif
      </div>
    </div>
    <!-- END Edit Assignment Block -->
  </div>
  <!-- END Page Content -->
@endsection