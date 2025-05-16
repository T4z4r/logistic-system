@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Edit Driver</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Update driver details</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('drivers.list') }}">Drivers</a>
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
    <!-- Edit Driver Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Edit Driver Form</h3>
      </div>
      <div class="block-content">
        @if (isset($driver) && $driver->id)
          <form action="{{ route('drivers.update', $driver->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
              <div class="col-md-6 mb-4">
                <label class="form-label" for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $driver->name) }}">
              </div>

              <div class="col-md-6 mb-4">
                <label class="form-label" for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $driver->email) }}">
              </div>

              <div class="col-md-6 mb-4">
                <label class="form-label" for="password">Password (leave blank to keep current)</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
              </div>

              <div class="col-md-6 mb-4">
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
              </div>

              <div class="col-md-6 mb-4">
                <label class="form-label" for="department_id">Department</label>
                <select name="department_id" id="department_id" class="form-control @error('department_id') is-invalid @enderror">
                  <option value="">Select Department</option>
                  @foreach ($departments as $department)
                    <option value="{{ $department->id }}" {{ old('department_id', $driver->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-6 mb-4">
                <label class="form-label" for="line_manager_id">Line Manager</label>
                <select name="line_manager_id" id="line_manager_id" class="form-control @error('line_manager_id') is-invalid @enderror">
                  <option value="">Select Line Manager</option>
                  @foreach ($managers as $manager)
                    <option value="{{ $manager->id }}" {{ old('line_manager_id', $driver->line_manager_id) == $manager->id ? 'selected' : '' }}>{{ $manager->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-6 mb-4">
                <label class="form-label" for="created_by">Created By</label>
                <select name="created_by" id="created_by" class="form-control @error('created_by') is-invalid @enderror">
                  <option value="">Select User</option>
                  @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('created_by', $driver->created_by) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-6 mb-4">
                <label class="form-label" for="status">Status</label>
                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                  <option value="1" {{ old('status', $driver->status) == 1 ? 'selected' : '' }}>Active</option>
                  <option value="0" {{ old('status', $driver->status) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
              </div>
            </div>
            <button type="submit" class="btn btn-primary">Update Driver</button>
          </form>
        @else
          <div class="alert alert-danger" role="alert">Driver not found.</div>
        @endif
      </div>
    </div>
    <!-- END Edit Driver Block -->
  </div>
  <!-- END Page Content -->
@endsection
