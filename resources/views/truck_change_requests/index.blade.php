@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light mt-5">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">
            Truck Change Requests
          </h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">
            View and manage all truck change requests
          </h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ url('/') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
              Truck Change Requests
            </li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content p-2">
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">All Requests</h3>
        <a href="{{ route('truck-change-requests.create') }}" class="btn btn-sm btn-primary">New Request</a>
      </div>
      <div class="block-content">
        @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped table-vcenter">
          <thead>
            <tr>
              <th>ID</th>
              <th>Allocation</th>
              <th>Reason</th>
              <th>Requested By</th>
              <th>Status</th>
              <th>Approval</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($requests as $req)
              <tr>
                <td>{{ $req->id }}</td>
                <td>#{{ $req->allocation_id }}</td>
                <td>{{ Str::limit($req->reason, 50) }}</td>
                <td>{{ $req->requester->name ?? 'N/A' }}</td>
                <td>{{ $req->status }}</td>
                <td>{{ $req->approval_status }}</td>
                <td>
                  <a href="{{ route('truck-change-requests.edit', $req->id) }}" class="btn btn-sm btn-warning">Edit</a>
                  <form action="{{ route('truck-change-requests.destroy', $req->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

      </div>
    </div>
  </div>
  <!-- END Page Content -->
@endsection

