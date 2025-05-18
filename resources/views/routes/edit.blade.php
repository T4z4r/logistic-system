@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Edit Route</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Update route details</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('routes.list') }}">Routes</a>
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
    <!-- Edit Route Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Edit Route Form</h3>
      </div>
      <div class="block-content">
        @if (isset($route) && $route->id)
          <form action="{{ route('routes.update', $route->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
              <label class="form-label" for="name">Route Name</label>
              <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $route->name) }}">
        
            </div>
            <div class="mb-4">
              <label class="form-label" for="start_point">Start Point</label>
              <input type="text" name="start_point" id="start_point" class="form-control @error('start_point') is-invalid @enderror" value="{{ old('start_point', $route->start_point) }}">
      
            </div>
            <div class="mb-4">
              <label class="form-label" for="destination">Destination</label>
              <input type="text" name="destination" id="destination" class="form-control @error('destination') is-invalid @enderror" value="{{ old('destination', $route->destination) }}">
      
            </div>
            <div class="mb-4">
              <label class="form-label" for="estimated_distance">Estimated Distance (km)</label>
              <input type="number" step="0.01" name="estimated_distance" id="estimated_distance" class="form-control @error('estimated_distance') is-invalid @enderror" value="{{ old('estimated_distance', $route->estimated_distance) }}">
       
            </div>
            <div class="mb-4">
              <label class="form-label" for="estimated_days">Estimated Days</label>
              <input type="number" name="estimated_days" id="estimated_days" class="form-control @error('estimated_days') is-invalid @enderror" value="{{ old('estimated_days', $route->estimated_days) }}">
          
            </div>
            <div class="mb-4">
              <label class="form-label" for="status">Status</label>
              <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                <option value="1" {{ old('status', $route->status) == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('status', $route->status) == 0 ? 'selected' : '' }}>Inactive</option>
              </select>
       
            </div>
            <div class="mb-4">
              <label class="form-label" for="created_by">Created By</label>
              <select name="created_by" id="created_by" class="form-control @error('created_by') is-invalid @enderror">
                <option value="">Select User</option>
                @foreach ($users as $user)
                  <option value="{{ $user->id }}" {{ old('created_by', $route->created_by) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
              </select>
       
            </div>
            <button type="submit" class="btn btn-primary">Update Route</button>
          </form>
        @else
          <div class="alert alert-danger" role="alert">Route not found.</div>
        @endif
      </div>
    </div>
    <!-- END Edit Route Block -->
  </div>
  <!-- END Page Content -->
@endsection