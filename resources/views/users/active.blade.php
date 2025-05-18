@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light mt-5">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h5 class="h5 fw-bold mb-1">Active Users</h5>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">View all active users in the system</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('users.index') }}">Users</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Active</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content p-2">
    <!-- Active Users Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Active Users List</h3>
        <div class="block-options">
          <a href="{{ route('users.create') }}" class="btn btn-primary">Add New User</a>
          <a href="{{ route('users.inactive') }}" class="btn btn-secondary">View Inactive Users</a>
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
              <th>Email</th>
              <th>Department</th>
              <th>Position</th>
              <th>Line Manager</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($users as $user)
              <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->department?->name ?? 'N/A' }}</td>
                <td>{{ $user->position?->name ?? 'N/A' }}</td>
                <td>{{ $user->lineManager?->name ?? 'N/A' }}</td>
                <td>{{ $user->status ? 'Active' : 'Inactive' }}</td>
                <td>
                  <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-primary">Edit</a>
                  <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        {{ $users->links() }}
      </div>
    </div>
    <!-- END Active Users Block -->
  </div>
  <!-- END Page Content -->
@endsection
