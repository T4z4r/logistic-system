@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Create Approval Level</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Add a new level for {{ $approval->process_name }}</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('approvals.list') }}">Approvals</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Create Level</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content">
    <!-- Create Approval Level Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">New Approval Level Form</h3>
      </div>
      <div class="block-content">
        <form action="{{ route('approvals.levels.store', $approval->id) }}" method="POST">
          @csrf
          <div class="mb-4">
            <label class="form-label" for="role_id">Role</label>
            <select name="role_id" id="role_id" class="form-control @error('role_id') is-invalid @enderror">
              <option value="">Select Role</option>
              @foreach ($roles as $role)
                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
              @endforeach
            </select>
            @error('role_id')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="level_name">Level Name</label>
            <input type="text" name="level_name" id="level_name" class="form-control @error('level_name') is-invalid @enderror" value="{{ old('level_name') }}">
            @error('level_name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="rank">Rank</label>
            <input type="text" name="rank" id="rank" class="form-control @error('rank') is-invalid @enderror" value="{{ old('rank') }}">
            @error('rank')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="label_name">Label Name</label>
            <input type="text" name="label_name" id="label_name" class="form-control @error('label_name') is-invalid @enderror" value="{{ old('label_name') }}">
            @error('label_name')
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
          <button type="submit" class="btn btn-primary">Create Approval Level</button>
        </form>
      </div>
    </div>
    <!-- END Create Approval Level Block -->
  </div>
  <!-- END Page Content -->
@endsection
