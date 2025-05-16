@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Active Trailers</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">View all active trailers in the system</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('trailers.list') }}">Trailers</a>
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
    <!-- Active Trailers Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Active Trailers List</h3>
        <div class="block-options">
          <a href="{{ route('trailers.create') }}" class="btn btn-primary">Add New Trailer</a>
          <a href="{{ route('trailers.inactive') }}" class="btn btn-secondary">View Inactive Trailers</a>
        </div>
      </div>
      <div class="block-content">
        @if (session('success'))
          <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Plate Number</th>
              <th>Manufacturer</th>
              <th>Trailer Type</th>
              <th>Added By</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($trailers as $trailer)
              <tr>
                <td>{{ $trailer->plate_number }}</td>
                <td>{{ $trailer->manufacturer }}</td>
                <td>{{ $trailer->trailer_type }}</td>
                <td>{{ $trailer->addedBy?->name ?? 'N/A' }}</td>
                <td>{{ $trailer->status ? 'Active' : 'Inactive' }}</td>
                <td>
                  <a href="{{ route('trailers.edit', $trailer->id) }}" class="btn btn-sm btn-primary">Edit</a>
                  <form action="{{ route('trailers.delete', $trailer->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        {{ $trailers->links() }}
      </div>
    </div>
    <!-- END Active Trailers Block -->
  </div>
  <!-- END Page Content -->
@endsection
