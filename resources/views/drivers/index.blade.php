@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light mt-5">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
        <div class="flex-grow-1">
          <h5 class="h5 fw-bold mb-1">Driver Management</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage all drivers in the system</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Drivers</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content p-2">
    <!-- Drivers Block -->
    <div class="block block-rounded rounded-0">
      <div class="block-header block-header-default ">
        <h3 class="block-title">Drivers Overview</h3>
        <div class="block-options">
          <a href="{{ route('drivers.create') }}" class="btn btn-primary btn-sm">
            <i class="fa fa-plus"></i>
              Add New Driver
            </a>
          <a href="{{ route('drivers.active') }}" class="btn btn-secondary btn-sm">View Active Drivers</a>
          <a href="{{ route('drivers.inactive') }}" class="btn btn-secondary btn-sm ">View Inactive Drivers</a>
        </div>
      </div>
      <div class="block-content rounded-0">
        @if (session('success'))
          <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped table-sm">
          <thead class="bg-secondary">
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Department</th>
              <th>Position</th>
              <th>Line Manager</th>
              <th>Created By</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($drivers as $driver)
              <tr>
                <td>{{ $driver->name }}</td>
                <td>{{ $driver->email }}</td>
                <td>{{ $driver->department?->name ?? 'N/A' }}</td>
                <td>{{ $driver->position?->name ?? 'N/A' }}</td>
                <td>{{ $driver->lineManager?->name ?? 'N/A' }}</td>
                <td>{{ $driver->createdBy?->name ?? 'N/A' }}</td>
                <td>{{ $driver->status ? 'Active' : 'Inactive' }}</td>
                <td>
                  <a href="{{ route('drivers.edit', $driver->id) }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-edit"></i>
                  </a>
                  <form action="{{ route('drivers.delete', $driver->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                        <i class="fa fa-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        {{ $drivers->links() }}
      </div>
    </div>
    <!-- END Drivers Block -->
  </div>
  <!-- END Page Content -->
@endsection
