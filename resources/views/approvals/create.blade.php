@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Create Approval Process</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Add a new approval process to the system</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('approvals.list') }}">Approvals</a>
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
    <!-- Create Approval Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">New Approval Process Form</h3>
      </div>
      <div class="block-content">
        <form action="{{ route('approvals.store') }}" method="POST">
          @csrf
          <div class="mb-4">
            <label class="form-label" for="process_name">Process Name</label>
            <input type="text" name="process_name" id="process_name" class="form-control @error('process_name') is-invalid @enderror" value="{{ old('process_name') }}">
            @error('process_name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="levels">Levels</label>
            <input type="number" name="levels" id="levels" class="form-control @error('levels') is-invalid @enderror" value="{{ old('levels', 0) }}">
            @error('levels')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="escallation">Escallation</label>
            <select name="escallation" id="escallation" class="form-control @error('escallation') is-invalid @enderror">
              <option value="1" {{ old('escallation', 0) == 1 ? 'selected' : '' }}>Yes</option>
              <option value="0" {{ old('escallation', 0) == 0 ? 'selected' : '' }}>No</option>
            </select>
            @error('escallation')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="escallation_time">Escallation Time (hours)</label>
            <input type="number" name="escallation_time" id="escallation_time" class="form-control @error('escallation_time') is-invalid @enderror" value="{{ old('escallation_time') }}">
            @error('escallation_time')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <button type="submit" class="btn btn-primary">Create Approval Process</button>
        </form>
      </div>
    </div>
    <!-- END Create Approval Block -->
  </div>
  <!-- END Page Content -->
@endsection
