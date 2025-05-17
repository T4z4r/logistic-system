@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Edit Approval Level</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Update level for {{ $approval->process_name }}</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('approvals.list') }}">Approvals</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Edit Level</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content">
    <!-- Edit Approval Level Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Edit Approval Level Form</h3>
      </div>
      <div class="block-content">
        @if (isset($approvalLevel) && $approvalLevel->id)
          <form action="{{ route('approvals.levels.update', [$approval->id, $approvalLevel->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
              <label class="form-label" for="role_id">Role</label>
              <select name="role_id" id="role_id" class="form-control @error('role_id') is-invalid @enderror">
                <option value="">Select Role</option>
                @foreach ($roles as $role)
                  <option value="{{ $role->id }}" {{ old('role_id', $approvalLevel->role_id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
              </select>
          
            </div>
            <div class="mb-4">
              <label class="form-label" for="level_name">Level Name</label>
              <input type="text" name="level_name" id="level_name" class="form-control @error('level_name') is-invalid @enderror" value="{{ old('level_name', $approvalLevel->level_name) }}">

            </div>
            <div class="mb-4">
              <label class="form-label" for="rank">Rank</label>
              <input type="text" name="rank" id="rank" class="form-control @error('rank') is-invalid @enderror" value="{{ old('rank', $approvalLevel->rank) }}">

            </div>
            <div class="mb-4">
              <label class="form-label" for="label_name">Label Name</label>
              <input type="text" name="label_name" id="label_name" class="form-control @error('label_name') is-invalid @enderror" value="{{ old('label_name', $approvalLevel->label_name) }}">

            </div>
            <div class="mb-4">
              <label class="form-label" for="status">Status</label>
              <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                <option value="1" {{ old('status', $approvalLevel->status) == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('status', $approvalLevel->status) == 0 ? 'selected' : '' }}>Inactive</option>
              </select>

            </div>
            <button type="submit" class="btn btn-primary">Update Approval Level</button>
          </form>
        @else
          <div class="alert alert-danger" role="alert">Approval level not found.</div>
        @endif
      </div>
    </div>
    <!-- END Edit Approval Level Block -->
  </div>
  <!-- END Page Content -->
@endsection
