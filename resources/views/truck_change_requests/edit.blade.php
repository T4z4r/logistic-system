@extends('layouts.backend')

@section('content')
<!-- Hero -->
<div class="bg-body-light mt-5">
  <div class="content content-full">
    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
      <div class="flex-grow-1">
        <h1 class="h3 fw-bold mb-1">Edit Truck Change Request</h1>
        <h2 class="fs-base lh-base fw-medium text-muted mb-0">Update request information</h2>
      </div>
      <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
          <li class="breadcrumb-item"><a href="{{ route('truck-change-requests.index') }}">Requests</a></li>
          <li class="breadcrumb-item" aria-current="page">Edit</li>
        </ol>
      </nav>
    </div>
  </div>
</div>
<!-- END Hero -->

<div class="content p-2">
  <div class="block block-rounded">
    <div class="block-header block-header-default">
      <h3 class="block-title">Edit Request</h3>
    </div>
    <div class="block-content">
      <form action="{{ route('truck-change-requests.update', $request->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label class="form-label">Allocation ID</label>
          <input type="number" name="allocation_id" class="form-control" value="{{ $request->allocation_id }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Reason</label>
          <textarea name="reason" class="form-control" rows="4" required>{{ $request->reason }}</textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-control">
            <option value="0" {{ $request->status == 0 ? 'selected' : '' }}>Pending</option>
            <option value="1" {{ $request->status == 1 ? 'selected' : '' }}>In Progress</option>
            <option value="2" {{ $request->status == 2 ? 'selected' : '' }}>Completed</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Approval Status</label>
          <select name="approval_status" class="form-control">
            <option value="0" {{ $request->approval_status == 0 ? 'selected' : '' }}>Not Reviewed</option>
            <option value="1" {{ $request->approval_status == 1 ? 'selected' : '' }}>Approved</option>
            <option value="2" {{ $request->approval_status == 2 ? 'selected' : '' }}>Rejected</option>
          </select>
        </div>

        <button type="submit" class="btn btn-success">Update Request</button>
      </form>
    </div>
  </div>
</div>
@endsection

