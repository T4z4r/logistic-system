@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Active Routes</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">View all active routes in the system</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('routes.list') }}">Routes</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Active</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content">
    <!-- Active Routes Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Active Routes List</h3>
        <div class="block-options">
          <a href="{{ route('routes.create') }}" class="btn btn-primary">Add New Route</a>
          <a href="{{ route('routes.inactive') }}" class="btn btn-secondary">View Inactive Routes</a>
        </div>
      </div>
      <div class="block-content">
        @if (session('success'))
          <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Name</th>
              <th>Start Point</th>
              <th>Destination</th>
              <th>Created By</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($routes as $route)
              <tr>
                <td>{{ $route->name }}</td>
                <td>{{ $route->start_point }}</td>
                <td>{{ $route->destination }}</td>
                <td>{{ $route->createdBy?->name ?? 'N/A' }}</td>
                <td>{{ $route->status ? 'Active' : 'Inactive' }}</td>
                <td>
                  <a href="{{ route('routes.edit', $route->id) }}" class="btn btn-sm btn-primary">Edit</a>
                  <form action="{{ route('routes.delete', $route->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        {{ $routes->links() }}
      </div>
    </div>
    <!-- END Active Routes Block -->
  </div>
  <!-- END Page Content -->
@endsection
