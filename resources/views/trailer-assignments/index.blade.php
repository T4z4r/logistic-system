@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Trailer Assignments</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage trailer and truck assignments</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Trailer Assignments</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content">
    <!-- Trailer Assignments Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Trailer Assignments Overview</h3>
        <div class="block-options">
          <a href="{{ route('trailer-assignments.create') }}" class="btn btn-primary">Add New Assignment</a>
          <a href="{{ route('trailer-assignments.active') }}" class="btn btn-success">Active</a>
          <a href="{{ route('trailer-assignments.inactive') }}" class="btn btn-warning">Inactive</a>
        </div>
      </div>
      <div class="block-content">
        @if (session('success'))
          <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Trailer</th>
              <th>Truck</th>
              <th>Assigned By</th>
              <th>Status</th>
              <th>Created At</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($assignments as $assignment)
              <tr>
                <td>{{ $assignment->trailer?->plate_number ?? 'N/A' }}</td>
                <td>{{ $assignment->truck?->plate_number ?? 'N/A' }}</td>
                <td>{{ $assignment->assignedBy?->name ?? 'N/A' }}</td>
                <td>{{ $assignment->status ? 'Active' : 'Inactive' }}</td>
                <td>{{ $assignment->created_at?->format('Y-m-d H:i') }}</td>
                <td>
                  <a href="{{ route('trailer-assignments.edit', $assignment->id) }}" class="btn btn-sm btn-primary">Edit</a>
                  <form action="{{ route('trailer-assignments.delete', $assignment->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        {{ $assignments->links() }}
      </div>
    </div>
    <!-- END Trailer Assignments Block -->
  </div>
  <!-- END Page Content -->
@endsection