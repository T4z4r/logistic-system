@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light mt-5">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Create Driver</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Add a new driver to the system</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('drivers.list') }}">Drivers</a>
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
    <!-- Create Driver Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">New Driver Form</h3>
      </div>
      <div class="block-content">
        <form action="{{ route('drivers.store') }}" method="POST">
          @csrf
          <div class="row">
            <div class="col-md-6 mb-4">
              <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
              <input type="text" name="name" id="name" placeholder="Enter driver name" 
                     class="form-control @error('name') is-invalid @enderror" 
                     value="{{ old('name') }}" required>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-4">
              <label class="form-label" for="department_id">Department</label>
              <select name="department_id" id="department_id" 
                      class="form-control @error('department_id') is-invalid @enderror">
                <option value="">Select Department</option>
                @foreach ($departments as $department)
                  <option value="{{ $department->id }}" 
                          {{ old('department_id') == $department->id ? 'selected' : '' }}>
                          {{ htmlspecialchars($department->name) }}
                  </option>
                @endforeach
              </select>
              @error('department_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-4">
              <label class="form-label" for="line_manager_id">Line Manager</label>
              <select name="line_manager_id" id="line_manager_id" 
                      class="form-control @error('line_manager_id') is-invalid @enderror">
                <option value="">Select Line Manager</option>
                @foreach ($managers as $manager)
                  <option value="{{ $manager->id }}" 
                          {{ old('line_manager_id') == $manager->id ? 'selected' : '' }}>
                          {{ htmlspecialchars($manager->name) }}
                  </option>
                @endforeach
              </select>
              @error('line_manager_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-4">
              <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
              <select name="status" id="status" 
                      class="form-control @error('status') is-invalid @enderror" required>
                <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
              </select>
              @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-12 text-end">
              <button type="submit" class="btn btn-primary mb-3">Create Driver</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- END Create Driver Block -->
  </div>
  <!-- END Page Content -->
@endsection