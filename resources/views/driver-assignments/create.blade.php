@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Create Driver Assignment</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Assign a driver to a truck</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('driver-assignments.list') }}">Driver Assignments</a>
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
    <!-- Create Assignment Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">New Driver Assignment Form</h3>
      </div>
      <div class="block-content">
        <form action="{{ route('driver-assignments.store') }}" method="POST">
          @csrf
          <div class="mb-4">
            <label class="form-label" for="driver_id">Driver</label>
            <select name="driver_id" id="driver_id" class="form-control @error('driver_id') is-invalid @enderror">
              <option value="">Select Driver</option>
              @foreach ($drivers as $driver)
                <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
              @endforeach
            </select>
            @error('driver_id')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="truck_id">Truck</label>
            <select name="truck_id" id="truck_id" class="form-control @error('truck_id') is-invalid @enderror">
              <option value="">Select Truck</option>
              @foreach ($trucks as $truck)
                <option value="{{ $truck->id }}" {{ old('truck_id') == $truck->id ? 'selected' : '' }}>{{ $truck->license_plate }}</option>
              @endforeach
            </select>
            @error('truck_id')
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
          <button type="submit" class="btn btn-primary">Create Assignment</button>
        </form>
      </div>
    </div>
    <!-- END Create Assignment Block -->
  </div>
  <!-- END Page Content -->
@endsection