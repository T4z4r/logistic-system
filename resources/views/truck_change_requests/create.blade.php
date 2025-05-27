@extends('layouts.backend')

@section('content')
<!-- Hero -->
<div class="bg-body-light mt-5">
  <div class="content content-full">
    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
      <div class="flex-grow-1">
        <h1 class="h3 fw-bold mb-1">New Truck Change Request</h1>
        <h2 class="fs-base lh-base fw-medium text-muted mb-0">Fill in the request details</h2>
      </div>
      <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
          <li class="breadcrumb-item"><a href="{{ route('truck-change-requests.index') }}">Requests</a></li>
          <li class="breadcrumb-item" aria-current="page">Create</li>
        </ol>
      </nav>
    </div>
  </div>
</div>
<!-- END Hero -->

<div class="content p-2">
  <div class="block block-rounded">
    <div class="block-header block-header-default">
      <h3 class="block-title">Create Request</h3>
    </div>
    <div class="block-content">
      <form action="{{ route('truck-change-requests.store') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label class="form-label">Allocation ID</label>
          <input type="number" name="allocation_id" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Reason</label>
          <textarea name="reason" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-control">
            <option value="0">Pending</option>
            <option value="1">In Progress</option>
            <option value="2">Completed</option>
          </select>
        </div>

        <button type="submit" class="btn btn-primary">Submit Request</button>
      </form>
    </div>
  </div>
</div>
@endsection
